<?php
/**
 * Functions for working with custom module setup.
 *
 * @author surrealwebs <surrealwebs@gmail.com>
 */


class SSteveCustomModules {
	/**
	 * Loop through and output ACF flexible content blocks for the current page.
	 *
	 * If called without an argument, will attempt to determine if this is for a post, term, user, or comment.
	 * Passing an int implies a post ID.
	 * Pass the specific desired object otherwise.
	 *
	 * @param bool|int|\WP_Post|\WP_Term|\WP_User|\WP_Comment $id_or_object Defaults to global queried object.
	 */
	public static function the_content_blocks( $id_or_object = false ) {

		/**
		 * This relies on ACF. If ACF is not available, bail.
		 */
		if ( ! function_exists( 'have_rows' ) ) {
			return;
		}

		if ( empty( $id_or_object ) ) {
			/**
			 * If you're on a single post of any type, it will return the \WP_Post object.
			 * If you're on a post type archive, it will return the \WP_Post_Type object.
			 * If you're on a term archive, it will return the \WP_Term object.
			 * If you're on an author archive, it will return the \WP_User object.
			 * If you're on a comment, it will return the \WP_Comment object.
			 *
			 * @var null|\WP_Post|\WP_Term|\WP_User|\WP_Comment $id_or_object
			 */
			$id_or_object = get_queried_object();
		}

		if ( ! SSteveCustomModules::have_content_blocks( $id_or_object ) ) {
			return;
		}

		/**
		 * Once again, int implies a post ID.
		 */
		if ( is_int( $id_or_object ) || $id_or_object instanceof WP_Post ) {
			if ( post_password_required( $id_or_object ) ) {
				SSteveCustomModules::the_password_form( $id_or_object );
				return;
			}
		}

		/*
		 * For clarity, have_rows() is not a WordPress Core function,
		 * it is an ACF function for looping through Flex Content or Repeater Fields.
		 */
		while ( have_rows( 'content_blocks', $id_or_object ) ) {

			/*
			 * For clarity, the_row() is not a WordPress Core function,
			 * it is an ACF function that progresses the global repeater or flexible content value 1 row.
			 */
			the_row();

			/*
			 * Template part 'name' MUST match the ACF flex layout name.
			 * For example, given a flex layout name of 'hero', the template part file must be named block-hero.php.
			 * Use underscores in flex layout names, don't use hyphens.
			 * So for example, use block-example_layout, not block-example-layout.
			 */
			get_template_part( 'template-parts/content-blocks/block', get_row_layout() );
		}

		wp_reset_postdata();
	}

	/**
	 * Conditional check for content_blocks for the given object.
	 *
	 * If called without an argument, will attempt to determine if this is for a post, term, user, or comment.
	 * Passing an int implies a post ID.
	 * Pass the specific desired object otherwise.
	 *
	 * @param bool|int|\WP_Post|\WP_Term|\WP_User|\WP_Comment $id_or_object Defaults to global queried object.
	 *
	 * @return bool
	 */
	public static function have_content_blocks( $id_or_object = false ) {

		if ( empty( $id_or_object ) ) {
			/**
			 * If you're on a single post of any type, it will return the \WP_Post object.
			 * If you're on a post type archive, it will return the \WP_Post_Type object.
			 * If you're on a term archive, it will return the \WP_Term object.
			 * If you're on an author archive, it will return the \WP_User object.
			 * If you're on a comment, it will return the \WP_Comment object.
			 *
			 * @var null|\WP_Post|\WP_Term|\WP_User|\WP_Comment $id_or_object
			 */
			$id_or_object = get_queried_object();
		}

		/**
		 * If get_X_meta returns an empty string, or an empty array, we don't have blocks.
		 * ACF stores the content_blocks flex fields as a serialized string like so,
		 * a:2:{i:0;s:4:"hero";i:1;s:15:"generic_content";}
		 *
		 * @var array|string|false $content_blocks
		 */
		$content_blocks = false;

		/**
		 * For numeric input, we assume it's a post_id, if you didn't want that, pass in the desired object.
		 */
		if ( is_numeric( $id_or_object ) ) {
			$content_blocks = get_post_meta( $id_or_object, 'content_blocks', true );
		}

		if ( $id_or_object instanceof WP_Post ) {
			$content_blocks = get_post_meta( $id_or_object->ID, 'content_blocks', true );
		}

		if ( $id_or_object instanceof WP_Term ) {
			$content_blocks = get_term_meta( $id_or_object->term_id, 'content_blocks', true );
		}

		if ( $id_or_object instanceof WP_User ) {
			// @codingStandardsIgnoreStart
			// VIP rule that I can't seem to squash here.
			$content_blocks = get_user_meta( $id_or_object->ID, 'content_blocks', true );
			// @codingStandardsIgnoreEnd
		}

		if ( $id_or_object instanceof WP_Comment ) {
			$content_blocks = get_comment_meta( $id_or_object->comment_ID, 'content_blocks', true );
		}

		if ( empty( $content_blocks ) || ! is_array( $content_blocks ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the ACF ID string for a given object type for field retrieval.
	 *
	 * If called without an argument, will attempt to determine if this is for a post, term, user, or comment.
	 * Passing an int implies a post ID.
	 * Pass the specific desired object otherwise.
	 *
	 * The below is based on logic found inside ACF Pro v 5.6.1's acf_get_valid_post_id function.
	 * Most ACF functions have a parameter for passing a string (usually called $post_id) to get meta.
	 * For fields on objects other than posts, ACF enforces specific string formatting to map the ID to correct meta type.
	 * So prefixes for comments and users like 'comment_' . $WP_Comment->comment_ID, 'user_' . $WP_User->ID.
	 * Terms are treated a little differently, like $WP_Term->taxonomy . '_' . $WP_Term->term_id
	 *
	 * @see acf_get_valid_post_id
	 *
	 * @param bool|int|\WP_Post|\WP_Term|\WP_User|\WP_Comment $id_or_object Defaults to global queried object.
	 *
	 * @return int|string
	 */
	public static function get_acf_id( $id_or_object = false ) {

		if ( empty( $id_or_object ) ) {
			/**
			 * If you're on a single post of any type, it will return the \WP_Post object.
			 * If you're on a post type archive, it will return the \WP_Post_Type object.
			 * If you're on a term archive, it will return the \WP_Term object.
			 * If you're on an author archive, it will return the \WP_User object.
			 * If you're on a comment, it will return the \WP_Comment object.
			 *
			 * @var null|\WP_Post|\WP_Term|\WP_User|\WP_Comment $id_or_object
			 */
			$id_or_object = get_queried_object();
		}

		if ( is_numeric( $id_or_object ) ) {
			return absint( $id_or_object );
		}

		if ( $id_or_object instanceof WP_Post ) {
			return $id_or_object->ID;
		}

		if ( $id_or_object instanceof WP_Term ) {
			return $id_or_object->taxonomy . '_' . $id_or_object->term_id;
		}

		if ( $id_or_object instanceof WP_User ) {
			return 'user_' . $id_or_object->ID;
		}

		if ( $id_or_object instanceof WP_Comment ) {
			return 'comment_' . $id_or_object->comment_ID;
		}

		return '';
	}

	/**
	 * Print the post password form markup.
	 *
	 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
	 */
	public static function the_password_form( $post = 0 ) {
		echo SSteveCustomModules::get_the_password_form( $post ); // WPCS: XSS ok.
	}

	/**
	 * Get the post password form markup.
	 *
	 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
	 *
	 * @return string
	 */
	public static function get_the_password_form( $post = 0 ) {
		return sprintf(
			'<div class="post-password-wrapper">%s</div>',
			get_the_password_form( $post )
		);
	}

}

