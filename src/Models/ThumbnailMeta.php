<?php

namespace Wizhi\Models;


/**
 * 附件元数据类
 */
class ThumbnailMeta extends PostMeta
{
    const SIZE_THUMBNAIL = 'thumbnail';
    const SIZE_MEDIUM    = 'medium';
    const SIZE_LARGE     = 'large';
    const SIZE_FULL      = 'full';

    protected $with = ['attachment'];


	/**
	 * 和附件的关系
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
    public function attachment()
    {
        return $this->belongsTo('Wizhi\Models\Attachment', 'meta_value');
    }


	/**
	 * 转为字符串
	 *
	 * @return mixed
	 */
    public function __toString()
    {
        return $this->attachment->guid;
    }

	/**
	 * 尺寸
	 *
	 * @param $size
	 *
	 * @return string
	 * @throws \Exception
	 */
    public function size($size)
    {
        if ($size  == self::SIZE_FULL) {
            return $this->attachment->url;
        }

        $sizes = $this->attachment->meta->_wp_attachment_metadata['sizes'];

        if (! isset($sizes[$size])) {
            throw new \Exception('Invalid size: ' . $size);
        }

        return dirname($this->attachment->url)
            . '/'
            . $sizes[$size]['file'];
    }
}
