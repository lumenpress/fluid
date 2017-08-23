<?php

namespace Lumenpress\ORM\Models;

abstract class AbstractPost extends Model
{
    protected static $registeredPostTypes = [
        'post' => Post::class,
        'page' => Page::class
    ];

    const CREATED_AT = 'post_date';

    const UPDATED_AT = 'post_modified';

    protected $table = 'posts';

    protected $primaryKey = 'ID';

    protected $foreignKey = 'post_id';

    protected $_slug;

    protected $dates = [
        'post_date', 
        'post_date_gmt', 
        'post_modified', 
        'post_modified_gmt'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->ID = 0;
        $this->post_title = 'Untitle';
        $this->post_parent = 0;
        $this->menu_order = 0;
        $this->post_status = 'publish';
        $this->comment_status = 'closed';
        $this->post_author = (int) lumenpress_get_current_user_id();
        $this->post_type = property_exists($this, 'postType') ? $this->postType : 'post';
    }

    public function meta($key = null)
    {
        $builder = $this->hasMany(PostMeta::class, 'post_id');
        if ($key) {
            $builder->where('meta_key', $key);
        }
        return $builder;
    }

    /**
     * Mutator for postTitle attribute.
     *
     * @return void
     */
    public function setPostTitleAttribute($value)
    {
        $this->attributes['post_title'] = $value;
        $this->setPostNameAttribute($value);
    }

    /**
     * Mutator for postType attribute.
     *
     * @return void
     */
    public function setPostTypeAttribute($value)
    {
        $this->attributes['post_type'] = $value;
        if ($this->_slug) {
            $this->setPostNameAttribute($this->_slug);
        }
    }

    /**
     * Mutator for post status attribute.
     *
     * @return void
     */
    public function setPostStatusAttribute($value)
    {
        $this->attributes['post_status'] = $value;
        if ($this->_slug) {
            $this->setPostNameAttribute($this->_slug);
        }
    }

    /**
     * Mutator for post parent attribute.
     *
     * @return void
     */
    public function setPostParentAttribute($value)
    {
        $this->attributes['post_parent'] = $value;
        if ($this->_slug) {
            $this->setPostNameAttribute($this->_slug);
        }
    }

    /**
     * Mutator for post name attribute.
     *
     * @return void
     */
    public function setPostNameAttribute($value)
    {
        $this->_slug = $value;
        $this->attributes['post_name'] = $this->getUniquePostName(
            str_slug($value), 
            $this->ID,
            $this->post_status, 
            $this->post_type,
            $this->post_parent
        );
    }

    /**
     * Accessor for post content attribute.
     *
     * @return returnType
     */
    public function getPostContentAttribute($value)
    {
        return luemnpress_get_the_content($value);
    }

    /**
     * Accessor for guid attribute.
     *
     * @return returnType
     */
    public function getGuidAttribute($value)
    {
        return $this->ID !== 0 ? lumenpress_get_permalink($this->ID) 
            : url(($this->post_type === 'page' ? '' : $this->post_type).'/'.$this->post_name);
    }

    public function getUniquePostName($slug, $id = 0, $status = 'publish', $type = 'post', $parent = 0)
    {
        $i = 1;
        $tmp = $slug;
        while (static::where('post_type', $type)
            ->where('ID', '!=', $id)
            ->where('post_parent', $parent)
            ->where('post_status', $status)
            ->where('post_name', $slug)->count() > 0) {
            $slug = $tmp . '-' . (++$i);
        }
        return $slug;
    }

    public function save(array $options = [])
    {
        if (!$this->_slug) {
            $this->post_name = $this->post_title;
        }
        if (!parent::save($options)) {
            return false;
        }
        $this->meta->save();
        return true;
    }

    public static function registerType($type, $class)
    {
        static::$registeredPostTypes[$type] = $class;
    }

    public static function getPostClassByType($type)
    {
        return array_get(static::$registeredPostTypes, $type, Post::class);
    }
}
