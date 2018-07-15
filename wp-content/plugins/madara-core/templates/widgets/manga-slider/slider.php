<?php global $wp_manga_functions; ?>
<div class="slider__item">
    <div class="slider__thumb">
        <div class="slider__thumb_item">
            <a href="<?php echo get_the_permalink() ?>">
                <?php the_post_thumbnail( 'manga-slider' ) ?>
                <div class="slider-overlay"></div>
            </a>
        </div>
    </div>
    <div class="slider__content">
        <div class="slider__content_item">
            <div class="post-title font-title">
                <h4>
                    <a href="<?php echo get_the_permalink() ?>"><?php echo get_the_title() ?></a>
                </h4>
            </div>
        </div>
    </div>
</div>