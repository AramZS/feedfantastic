<?php
class Watch_Tools {

	/**
     * Starts this class, call with this function.
     * @return [type] [description]
     */
    public static function init() {
        static $instance;
        if ( ! is_a( $instance, 'Watch_Tools' ) ) {
            $instance = new self();
        }
        return $instance;
    }

	/**
	 * Method for building date objects that can then be turned into whatever you please.
	 *
	 * This is nice, because you have a netral date object that can be output into any
	 * timezone or format you'd like.
	 * @param  [type] $raw_date_string A raw date string. Whatever the format, make sure it
	 *                                 matches the value passed to $format.
	 * @param  string $format          The date format.
	 * @param  string $zone            A PHP DateTimeZone-compatable timezone string.
	 * @return object                  A date time object.
	 */
	public function date_maker( $raw_date_string, $format = 'wordpress', $zone = 'UTC' ){
		if ( 'wordpress' == $format ){
			$format = 'Y-m-d H:i:s';
		}
		if ( 'now' == $raw_date_string ){
			$format = 'U';
			$raw_date_string = date('U');
		}
		$date_obj = DateTime::createFromFormat( $format, $raw_date_string, new DateTimeZone($zone) );
		return $date_obj;
		/**
		 * How to use this properly!
		 *
		 * Get the date object in whatever format you like!
		 * $date_obj->format('Y-m-d H:i:s');
		 *
		 * Set the date object's time zone like below and get the right time with the
		 * format function!
		 * Here's a list of timezones - http://php.net/manual/en/timezones.america.php
		 * $date_obj->setTimezone(new DateTimeZone('America/New_York'));
		 *
		 */
	}

	/**
	 * Get "now" as a WordPress-standard date-time format value.
	 *
	 * @return string WordPress format date-time string.
	 */
	public function post_date_as_now(){
		$date_obj = watch_tools()->date_maker('now');
		$date_obj->setTimezone( new DateTimeZone( get_option('timezone_string') ) );
		return $date_obj->format( 'Y-m-d H:i:s' );
	}

}


/**
 * Bootstrap
 *
 * You can also use this to get a value out of the global, eg
 *
 *    $foo = watch_tools()->bar;
 *
 * @since 1.0
 */
function watch_tools() {
    return Watch_Tools::init();
}
