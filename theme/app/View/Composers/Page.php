<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WP_Post;

class Page extends Composer
{
    /** @var array */
    protected static $views = [
        'page',
    ];

    /** Data halaman statis. */
    public function with(): array
    {
        $post = get_queried_object();
        if (! $post instanceof WP_Post) {
            return ['page' => null];
        }

        return [
            'page' => [
                'title' => html_entity_decode(get_the_title($post), ENT_QUOTES, 'UTF-8'),
                'content' => wp_kses_post(apply_filters('the_content', $post->post_content)),
            ],
        ];
    }
}
