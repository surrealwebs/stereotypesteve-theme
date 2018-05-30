<?php
/**
 * Utilites for the theme
 */


class SSteveUtilities {
	/**
	 * Get theme namespaced options.
	 *
	 * @param string $option_name Option to fetch.
	 * @param string $default     Optional default value to return.
	 *
	 * @return mixed|string
	 */
	public static function ssteve_get_option( $option_name, $default = '' ) {
		$options = get_option( 'ssteve_options', array() );

		if ( empty( $options ) || ! is_array( $options ) || ! isset( $options[ $option_name ] ) ) {
			return $default;
		}

		return $options[ $option_name ];
	}


}

