<?php

/**
 * Corcel\CommentBuilder.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Builder;

class CommentBuilder extends Builder
{
    /**
     * Where clause for only approved comments.
     *
     * @return \Wizhi\Models\CommentBuilder
     */
    public function approved()
    {
        return $this->where('comment_approved', 1);
    }
}
