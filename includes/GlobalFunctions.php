<?php
/**
 * Global functions used everywhere
 * @file
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "This file is part of MediaWiki, it is not a valid entry point" );
}

// Hide compatibility functions from Doxygen
/// @cond

/**
 * Compatibility functions
 *
 * We support PHP 5.2.3 and up.
 * Re-implementations of newer functions or functions in non-standard
 * PHP extensions may be included here.
 */

if( !function_exists( 'iconv' ) ) {
	/** @codeCoverageIgnore */
	function iconv( $from, $to, $string ) {
		return Fallback::iconv( $from, $to, $string );
	}
}

if ( !function_exists( 'mb_substr' ) ) {
	/** @codeCoverageIgnore */
	function mb_substr( $str, $start, $count='end' ) {
		return Fallback::mb_substr( $str, $start, $count );
	}
	/** @codeCoverageIgnore */
	function mb_substr_split_unicode( $str, $splitPos ) {
		return Fallback::mb_substr_split_unicode( $str, $splitPos );
	}
}

if ( !function_exists( 'mb_strlen' ) ) {
	/** @codeCoverageIgnore */
	function mb_strlen( $str, $enc = '' ) {
		return Fallback::mb_strlen( $str, $enc );
	}
}

if( !function_exists( 'mb_strpos' ) ) {
	/** @codeCoverageIgnore */
	function mb_strpos( $haystack, $needle, $offset = 0, $encoding = '' ) {
		return Fallback::mb_strpos( $haystack, $needle, $offset, $encoding );
	}

}

if( !function_exists( 'mb_strrpos' ) ) {
	/** @codeCoverageIgnore */
	function mb_strrpos( $haystack, $needle, $offset = 0, $encoding = '' ) {
		return Fallback::mb_strrpos( $haystack, $needle, $offset, $encoding );
	}
}


// Support for Wietse Venema's taint feature
if ( !function_exists( 'istainted' ) ) {
	/** @codeCoverageIgnore */
	function istainted( $var ) {
		return 0;
	}
	/** @codeCoverageIgnore */
	function taint( $var, $level = 0 ) {}
	/** @codeCoverageIgnore */
	function untaint( $var, $level = 0 ) {}
	define( 'TC_HTML', 1 );
	define( 'TC_SHELL', 1 );
	define( 'TC_MYSQL', 1 );
	define( 'TC_PCRE', 1 );
	define( 'TC_SELF', 1 );
}

/** Wikia change begin - backport hash_equals from MW 1.24 **/
// hash_equals function only exists in PHP >= 5.6.0
if ( !function_exists( 'hash_equals' ) ) {
	/**
	 * Check whether a user-provided string is equal to a fixed-length secret without
	 * revealing bytes of the secret through timing differences.
	 *
	 * This timing guarantee -- that a partial match takes the same time as a complete
	 * mismatch -- is why this function is used in some security-sensitive parts of the code.
	 * For example, it shouldn't be possible to guess an HMAC signature one byte at a time.
	 *
	 * Longer explanation: http://www.emerose.com/timing-attacks-explained
	 *
	 * @codeCoverageIgnore
	 * @param string $known_string Fixed-length secret to compare against
	 * @param string $user_string User-provided string
	 * @return bool True if the strings are the same, false otherwise
	 */
	function hash_equals( $known_string, $user_string ) {
		// Strict type checking as in PHP's native implementation
		if ( !is_string( $known_string ) ) {
			trigger_error( 'hash_equals(): Expected known_string to be a string, ' .
				gettype( $known_string ) . ' given', E_USER_WARNING );

			return false;
		}

		if ( !is_string( $user_string ) ) {
			trigger_error( 'hash_equals(): Expected user_string to be a string, ' .
				gettype( $user_string ) . ' given', E_USER_WARNING );

			return false;
		}

		// Note that we do one thing PHP doesn't: try to avoid leaking information about
		// relative lengths of $known_string and $user_string, and of multiple $known_strings.
		// However, lengths may still inevitably leak through, for example, CPU cache misses.
		$known_string_len = strlen( $known_string );
		$user_string_len = strlen( $user_string );
		$result = $known_string_len ^ $user_string_len;
		for ( $i = 0; $i < $user_string_len; $i++ ) {
			$result |= ord( $known_string[$i % $known_string_len] ) ^ ord( $user_string[$i] );
		}

		return ( $result === 0 );
	}
}
/** Wikia change -end **/
/// @endcond

/**
 * Like array_diff( $a, $b ) except that it works with two-dimensional arrays.
 * @param $a array
 * @param $b array
 * @return array
 */
function wfArrayDiff2( $a, $b ) {
	return array_udiff( $a, $b, 'wfArrayDiff2_cmp' );
}

/**
 * @param $a
 * @param $b
 * @return int
 */
function wfArrayDiff2_cmp( $a, $b ) {
	if ( !is_array( $a ) ) {
		return strcmp( $a, $b );
	} elseif ( count( $a ) !== count( $b ) ) {
		return count( $a ) < count( $b ) ? -1 : 1;
	} else {
		reset( $a );
		reset( $b );
		while( ( list( , $valueA ) = each( $a ) ) && ( list( , $valueB ) = each( $b ) ) ) {
			$cmp = strcmp( $valueA, $valueB );
			if ( $cmp !== 0 ) {
				return $cmp;
			}
		}
		return 0;
	}
}

/**
 * Array lookup
 * Returns an array where the values in the first array are replaced by the
 * values in the second array with the corresponding keys
 *
 * @param $a Array
 * @param $b Array
 * @return array
 */
function wfArrayLookup( $a, $b ) {
	return array_flip( array_intersect( array_flip( $a ), array_keys( $b ) ) );
}

/**
 * Appends to second array if $value differs from that in $default
 *
 * @param $key String|Int
 * @param $value Mixed
 * @param $default Mixed
 * @param $changed Array to alter
 */
function wfAppendToArrayIfNotDefault( $key, $value, $default, &$changed ) {
	if ( is_null( $changed ) ) {
		throw new MWException( 'GlobalFunctions::wfAppendToArrayIfNotDefault got null' );
	}
	if ( $default[$key] !== $value ) {
		$changed[$key] = $value;
	}
}

/**
 * Backwards array plus for people who haven't bothered to read the PHP manual
 * XXX: will not darn your socks for you.
 *
 * @param $array1 Array
 * @param [$array2, [...]] Arrays
 * @return Array
 */
function wfArrayMerge( $array1/* ... */ ) {
	$args = func_get_args();
	$args = array_reverse( $args, true );
	$out = array();
	foreach ( $args as $arg ) {
		$out += $arg;
	}
	return $out;
}

/**
 * Merge arrays in the style of getUserPermissionsErrors, with duplicate removal
 * e.g.
 *	wfMergeErrorArrays(
 *		array( array( 'x' ) ),
 *		array( array( 'x', '2' ) ),
 *		array( array( 'x' ) ),
 *		array( array( 'y' ) )
 *	);
 * returns:
 * 		array(
 *   		array( 'x', '2' ),
 *   		array( 'x' ),
 *   		array( 'y' )
 *   	)
 * @param varargs
 * @return Array
 */
function wfMergeErrorArrays( /*...*/ ) {
	$args = func_get_args();
	$out = array();
	foreach ( $args as $errors ) {
		foreach ( $errors as $params ) {
			# @todo FIXME: Sometimes get nested arrays for $params,
			# which leads to E_NOTICEs
			$spec = implode( "\t", $params );
			$out[$spec] = $params;
		}
	}
	return array_values( $out );
}

/**
 * Insert array into another array after the specified *KEY*
 *
 * @param $array Array: The array.
 * @param $insert Array: The array to insert.
 * @param $after Mixed: The key to insert after
 * @return Array
 */
function wfArrayInsertAfter( $array, $insert, $after ) {
	// Find the offset of the element to insert after.
	$keys = array_keys( $array );
	$offsetByKey = array_flip( $keys );

	$offset = $offsetByKey[$after];

	// Insert at the specified offset
	$before = array_slice( $array, 0, $offset + 1, true );
	$after = array_slice( $array, $offset + 1, count( $array ) - $offset, true );

	$output = $before + $insert + $after;

	return $output;
}

/**
 * Recursively converts the parameter (an object) to an array with the same data
 *
 * @param $objOrArray Object|Array
 * @param $recursive Bool
 * @return Array
 */
function wfObjectToArray( $objOrArray, $recursive = true ) {
	$array = array();
	if( is_object( $objOrArray ) ) {
		$objOrArray = get_object_vars( $objOrArray );
	}
	foreach ( $objOrArray as $key => $value ) {
		if ( $recursive && ( is_object( $value ) || is_array( $value ) ) ) {
			$value = wfObjectToArray( $value );
		}

		$array[$key] = $value;
	}

	return $array;
}

/**
 * Wrapper around array_map() which also taints variables
 *
 * @param  $function Callback
 * @param  $input Array
 * @return Array
 */
function wfArrayMap( $function, $input ) {
	$ret = array_map( $function, $input );
	foreach ( $ret as $key => $value ) {
		$taint = istainted( $input[$key] );
		if ( $taint ) {
			taint( $ret[$key], $taint );
		}
	}
	return $ret;
}

/**
 * Get a random decimal value between 0 and 1, in a way
 * not likely to give duplicate values for any realistic
 * number of articles.
 *
 * @return string
 */
function wfRandom() {
	# The maximum random value is "only" 2^31-1, so get two random
	# values to reduce the chance of dupes
	$max = mt_getrandmax() + 1;
	$rand = number_format( ( mt_rand() * $max + mt_rand() )
		/ $max / $max, 12, '.', '' );
	return $rand;
}

/**
 * We want some things to be included as literal characters in our title URLs
 * for prettiness, which urlencode encodes by default.  According to RFC 1738,
 * all of the following should be safe:
 *
 * ;:@&=$-_.+!*'(),
 *
 * But + is not safe because it's used to indicate a space; &= are only safe in
 * paths and not in queries (and we don't distinguish here); ' seems kind of
 * scary; and urlencode() doesn't touch -_. to begin with.  Plus, although /
 * is reserved, we don't care.  So the list we unescape is:
 *
 * ;:@$!*(),/
 *
 * However, IIS7 redirects fail when the url contains a colon (Bug 22709),
 * so no fancy : for IIS7.
 *
 * %2F in the page titles seems to fatally break for some reason.
 *
 * @param $s String:
 * @return string
*/
function wfUrlencode( $s ) {
	static $needle;
	if ( is_null( $s ) ) {
		$needle = null;
		return '';
	}

	if ( is_null( $needle ) ) {
		$needle = array( '%3B', '%40', '%24', '%21', '%2A', '%28', '%29', '%2C', '%2F' );
		if ( !isset( $_SERVER['SERVER_SOFTWARE'] ) || ( strpos( $_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS/7' ) === false ) ) {
			$needle[] = '%3A';
		}
	}

	$s = urlencode( $s );
	$s = str_ireplace(
		$needle,
		array( ';', '@', '$', '!', '*', '(', ')', ',', '/', ':' ),
		$s
	);

	return $s;
}

/**
 * This function takes two arrays as input, and returns a CGI-style string, e.g.
 * "days=7&limit=100". Options in the first array override options in the second.
 * Options set to null or false will not be output.
 *
 * @param $array1 Array ( String|Array )
 * @param $array2 Array ( String|Array )
 * @param $prefix String
 * @return String
 */
function wfArrayToCGI( $array1, $array2 = null, $prefix = '' ) {
	if ( !is_null( $array2 ) ) {
		$array1 = $array1 + $array2;
	}

	$cgi = '';
	foreach ( $array1 as $key => $value ) {
		/* Wikia change - begin ( Options set to false will be output ) */
		if ( !is_null($value) && $value !== '' ) {
		/* Wikia change - end */
			if ( $cgi != '' ) {
				$cgi .= '&';
			}
			if ( $prefix !== '' ) {
				$key = $prefix . "[$key]";
			}
			if ( is_array( $value ) ) {
				$firstTime = true;
				foreach ( $value as $k => $v ) {
					$cgi .= $firstTime ? '' : '&';
					if ( is_array( $v ) ) {
						$cgi .= wfArrayToCGI( $v, null, $key . "[$k]" );
					} else {
						$cgi .= urlencode( $key . "[$k]" ) . '=' . urlencode( $v );
					}
					$firstTime = false;
				}
			} else {
				if ( is_object( $value ) ) {
					$value = $value->__toString();
				}
				$cgi .= urlencode( $key ) . '=' . urlencode( $value );
			}
		}
	}
	return $cgi;
}

/**
 * This is the logical opposite of wfArrayToCGI(): it accepts a query string as
 * its argument and returns the same string in array form.  This allows compa-
 * tibility with legacy functions that accept raw query strings instead of nice
 * arrays.  Of course, keys and values are urldecode()d.
 *
 * @param $query String: query string
 * @return array Array version of input
 */
function wfCgiToArray( $query ) {
	if ( isset( $query[0] ) && $query[0] == '?' ) {
		$query = substr( $query, 1 );
	}
	$bits = explode( '&', $query );
	$ret = array();
	foreach ( $bits as $bit ) {
		if ( $bit === '' ) {
			continue;
		}
		if ( strpos( $bit, '=' ) === false ) {
			// Pieces like &qwerty become 'qwerty' => '' (at least this is what php does)
			$key = $bit;
			$value = '';
		} else {
			list( $key, $value ) = explode( '=', $bit );
		}
		$key = urldecode( $key );
		$value = urldecode( $value );
		if ( strpos( $key, '[' ) !== false ) {
			$keys = array_reverse( explode( '[', $key ) );
			$key = array_pop( $keys );
			$temp = $value;
			foreach ( $keys as $k ) {
				$k = substr( $k, 0, -1 );
				$temp = array( $k => $temp );
			}
			if ( isset( $ret[$key] ) ) {
				$ret[$key] = array_merge( $ret[$key], $temp );
			} else {
				$ret[$key] = $temp;
			}
		} else {
			$ret[$key] = $value;
		}
	}
	return $ret;
}

/**
 * Append a query string to an existing URL, which may or may not already
 * have query string parameters already. If so, they will be combined.
 *
 * @param $url String
 * @param $query Mixed: string or associative array
 * @return string
 */
function wfAppendQuery( $url, $query ) {
	if ( is_array( $query ) ) {
		$query = wfArrayToCGI( $query );
	}
	if( $query != '' ) {
		if( false === strpos( $url, '?' ) ) {
			$url .= '?';
		} else {
			$url .= '&';
		}
		$url .= $query;
	}
	return $url;
}

/**
 * Expand a potentially local URL to a fully-qualified URL.  Assumes $wgServer
 * is correct.
 *
 * The meaning of the PROTO_* constants is as follows:
 * PROTO_HTTP: Output a URL starting with http://
 * PROTO_HTTPS: Output a URL starting with https://
 * PROTO_RELATIVE: Output a URL starting with // (protocol-relative URL)
 * PROTO_CURRENT: Output a URL starting with either http:// or https:// , depending on which protocol was used for the current incoming request
 * PROTO_CANONICAL: For URLs without a domain, like /w/index.php , use $wgCanonicalServer. For protocol-relative URLs, use the protocol of $wgCanonicalServer
 * PROTO_INTERNAL: Like PROTO_CANONICAL, but uses $wgInternalServer instead of $wgCanonicalServer
 *
 * @todo this won't work with current-path-relative URLs
 * like "subdir/foo.html", etc.
 *
 * @param $url String: either fully-qualified or a local path + query
 * @param $defaultProto Mixed: one of the PROTO_* constants. Determines the
 *                             protocol to use if $url or $wgServer is
 *                             protocol-relative
 * @return string Fully-qualified URL, current-path-relative URL or false if
 *                no valid URL can be constructed
 */
function wfExpandUrl( $url, $defaultProto = PROTO_CURRENT ) {
	global $wgServer, $wgCanonicalServer, $wgInternalServer;
	$serverUrl = $wgServer;
	if ( $defaultProto === PROTO_CANONICAL ) {
		$serverUrl = $wgCanonicalServer;
	}
	// Make $wgInternalServer fall back to $wgServer if not set
	if ( $defaultProto === PROTO_INTERNAL && $wgInternalServer !== false ) {
		$serverUrl = $wgInternalServer;
	}
	if ( $defaultProto === PROTO_CURRENT ) {
		$defaultProto = WebRequest::detectProtocol() . '://';
	}

	// Analyze $serverUrl to obtain its protocol
	$bits = wfParseUrl( $serverUrl );
	$serverHasProto = $bits && $bits['scheme'] != '';

	if ( $defaultProto === PROTO_CANONICAL || $defaultProto === PROTO_INTERNAL ) {
		if ( $serverHasProto ) {
			$defaultProto = $bits['scheme'] . '://';
		} else {
			// $wgCanonicalServer or $wgInternalServer doesn't have a protocol. This really isn't supposed to happen
			// Fall back to HTTP in this ridiculous case
			$defaultProto = PROTO_HTTP;
		}
	}

	$defaultProtoWithoutSlashes = substr( $defaultProto, 0, -2 );

	if ( substr( $url, 0, 2 ) == '//' ) {
		$url = $defaultProtoWithoutSlashes . $url;
	} elseif ( substr( $url, 0, 1 ) == '/' ) {
		// If $serverUrl is protocol-relative, prepend $defaultProtoWithoutSlashes, otherwise leave it alone
		$url = ( $serverHasProto ? '' : $defaultProtoWithoutSlashes ) . $serverUrl . $url;
	}

	$bits = wfParseUrl( $url );
	if ( $bits && isset( $bits['path'] ) ) {
		$bits['path'] = wfRemoveDotSegments( $bits['path'] );
		return wfAssembleUrl( $bits );
	} elseif ( $bits ) {
		# No path to expand
		return $url;
	} elseif ( substr( $url, 0, 1 ) != '/' ) {
		# URL is a relative path
		return wfRemoveDotSegments( $url );
	}

	# Expanded URL is not valid.
	return false;
}

/**
 * This function will reassemble a URL parsed with wfParseURL.  This is useful
 * if you need to edit part of a URL and put it back together.
 *
 * This is the basic structure used (brackets contain keys for $urlParts):
 * [scheme][delimiter][user]:[pass]@[host]:[port][path]?[query]#[fragment]
 *
 * @todo Need to integrate this into wfExpandUrl (bug 32168)
 *
 * @since 1.19
 * @param $urlParts Array URL parts, as output from wfParseUrl
 * @return string URL assembled from its component parts
 */
function wfAssembleUrl( $urlParts ) {
	$result = '';

	if ( isset( $urlParts['delimiter'] ) ) {
		if ( isset( $urlParts['scheme'] ) ) {
			$result .= $urlParts['scheme'];
		}

		$result .= $urlParts['delimiter'];
	}

	if ( isset( $urlParts['host'] ) ) {
		if ( isset( $urlParts['user'] ) ) {
			$result .= $urlParts['user'];
			if ( isset( $urlParts['pass'] ) ) {
				$result .= ':' . $urlParts['pass'];
			}
			$result .= '@';
		}

		$result .= $urlParts['host'];

		if ( isset( $urlParts['port'] ) ) {
			$result .= ':' . $urlParts['port'];
		}
	}

	if ( isset( $urlParts['path'] ) ) {
		$result .= $urlParts['path'];
	}

	if ( isset( $urlParts['query'] ) ) {
		$result .= '?' . $urlParts['query'];
	}

	if ( isset( $urlParts['fragment'] ) ) {
		$result .= '#' . $urlParts['fragment'];
	}

	return $result;
}

/**
 * Remove all dot-segments in the provided URL path.  For example,
 * '/a/./b/../c/' becomes '/a/c/'.  For details on the algorithm, please see
 * RFC3986 section 5.2.4.
 *
 * @todo Need to integrate this into wfExpandUrl (bug 32168)
 *
 * @param $urlPath String URL path, potentially containing dot-segments
 * @return string URL path with all dot-segments removed
 */
function wfRemoveDotSegments( $urlPath ) {
	$output = '';
	$inputOffset = 0;
	$inputLength = strlen( $urlPath );

	while ( $inputOffset < $inputLength ) {
		$prefixLengthOne = substr( $urlPath, $inputOffset, 1 );
		$prefixLengthTwo = substr( $urlPath, $inputOffset, 2 );
		$prefixLengthThree = substr( $urlPath, $inputOffset, 3 );
		$prefixLengthFour = substr( $urlPath, $inputOffset, 4 );
		$trimOutput = false;

		if ( $prefixLengthTwo == './' ) {
			# Step A, remove leading "./"
			$inputOffset += 2;
		} elseif ( $prefixLengthThree == '../' ) {
			# Step A, remove leading "../"
			$inputOffset += 3;
		} elseif ( ( $prefixLengthTwo == '/.' ) && ( $inputOffset + 2 == $inputLength ) ) {
			# Step B, replace leading "/.$" with "/"
			$inputOffset += 1;
			$urlPath[$inputOffset] = '/';
		} elseif ( $prefixLengthThree == '/./' ) {
			# Step B, replace leading "/./" with "/"
			$inputOffset += 2;
		} elseif ( $prefixLengthThree == '/..' && ( $inputOffset + 3 == $inputLength ) ) {
			# Step C, replace leading "/..$" with "/" and
			# remove last path component in output
			$inputOffset += 2;
			$urlPath[$inputOffset] = '/';
			$trimOutput = true;
		} elseif ( $prefixLengthFour == '/../' ) {
			# Step C, replace leading "/../" with "/" and
			# remove last path component in output
			$inputOffset += 3;
			$trimOutput = true;
		} elseif ( ( $prefixLengthOne == '.' ) && ( $inputOffset + 1 == $inputLength ) ) {
			# Step D, remove "^.$"
			$inputOffset += 1;
		} elseif ( ( $prefixLengthTwo == '..' ) && ( $inputOffset + 2 == $inputLength ) ) {
			# Step D, remove "^..$"
			$inputOffset += 2;
		} else {
			# Step E, move leading path segment to output
			if ( $prefixLengthOne == '/' ) {
				$slashPos = strpos( $urlPath, '/', $inputOffset + 1 );
			} else {
				$slashPos = strpos( $urlPath, '/', $inputOffset );
			}
			if ( $slashPos === false ) {
				$output .= substr( $urlPath, $inputOffset );
				$inputOffset = $inputLength;
			} else {
				$output .= substr( $urlPath, $inputOffset, $slashPos - $inputOffset );
				$inputOffset += $slashPos - $inputOffset;
			}
		}

		if ( $trimOutput ) {
			$slashPos = strrpos( $output, '/' );
			if ( $slashPos === false ) {
				$output = '';
			} else {
				$output = substr( $output, 0, $slashPos );
			}
		}
	}

	return $output;
}

/**
 * Returns a regular expression of url protocols
 *
 * @param $includeProtocolRelative bool If false, remove '//' from the returned protocol list.
 *        DO NOT USE this directly, use wfUrlProtocolsWithoutProtRel() instead
 * @return String
 */
function wfUrlProtocols( $includeProtocolRelative = true ) {
	global $wgUrlProtocols;

	// Cache return values separately based on $includeProtocolRelative
	static $withProtRel = null, $withoutProtRel = null;
	$cachedValue = $includeProtocolRelative ? $withProtRel : $withoutProtRel;
	if ( !is_null( $cachedValue ) ) {
		return $cachedValue;
	}

	// Support old-style $wgUrlProtocols strings, for backwards compatibility
	// with LocalSettings files from 1.5
	if ( is_array( $wgUrlProtocols ) ) {
		$protocols = array();
		foreach ( $wgUrlProtocols as $protocol ) {
			// Filter out '//' if !$includeProtocolRelative
			if ( $includeProtocolRelative || $protocol !== '//' ) {
				$protocols[] = preg_quote( $protocol, '/' );
			}
		}

		$retval = implode( '|', $protocols );
	} else {
		// Ignore $includeProtocolRelative in this case
		// This case exists for pre-1.6 compatibility, and we can safely assume
		// that '//' won't appear in a pre-1.6 config because protocol-relative
		// URLs weren't supported until 1.18
		$retval = $wgUrlProtocols;
	}

	// Cache return value
	if ( $includeProtocolRelative ) {
		$withProtRel = $retval;
	} else {
		$withoutProtRel = $retval;
	}
	return $retval;
}

/**
 * Like wfUrlProtocols(), but excludes '//' from the protocol list. Use this if
 * you need a regex that matches all URL protocols but does not match protocol-
 * relative URLs
 * @return String
 */
function wfUrlProtocolsWithoutProtRel() {
	return wfUrlProtocols( false );
}

/**
 * parse_url() work-alike, but non-broken.  Differences:
 *
 * 1) Does not raise warnings on bad URLs (just returns false)
 * 2) Handles protocols that don't use :// (e.g., mailto: and news: , as well as protocol-relative URLs) correctly
 * 3) Adds a "delimiter" element to the array, either '://', ':' or '//' (see (2))
 *
 * @param $url String: a URL to parse
 * @return Array: bits of the URL in an associative array, per PHP docs
 */
function wfParseUrl( $url ) {
	global $wgUrlProtocols; // Allow all protocols defined in DefaultSettings/LocalSettings.php

	// Protocol-relative URLs are handled really badly by parse_url(). It's so bad that the easiest
	// way to handle them is to just prepend 'http:' and strip the protocol out later
	$wasRelative = substr( $url, 0, 2 ) == '//';
	if ( $wasRelative ) {
		$url = "http:$url";
	}
	wfSuppressWarnings();
	$bits = parse_url( $url );
	wfRestoreWarnings();
	// parse_url() returns an array without scheme for some invalid URLs, e.g.
	// parse_url("%0Ahttp://example.com") == array( 'host' => '%0Ahttp', 'path' => 'example.com' )
	if ( !$bits || !isset( $bits['scheme'] ) ) {
		return false;
	}

	// most of the protocols are followed by ://, but mailto: and sometimes news: not, check for it
	if ( in_array( $bits['scheme'] . '://', $wgUrlProtocols ) ) {
		$bits['delimiter'] = '://';
	} elseif ( in_array( $bits['scheme'] . ':', $wgUrlProtocols ) ) {
		$bits['delimiter'] = ':';
		// parse_url detects for news: and mailto: the host part of an url as path
		// We have to correct this wrong detection
		if ( isset( $bits['path'] ) ) {
			$bits['host'] = $bits['path'];
			$bits['path'] = '';
		}
	} else {
		return false;
	}

	/* Provide an empty host for eg. file:/// urls (see bug 28627) */
	if ( !isset( $bits['host'] ) ) {
		$bits['host'] = '';

		/* parse_url loses the third / for file:///c:/ urls (but not on variants) */
		if ( substr( $bits['path'], 0, 1 ) !== '/' ) {
			$bits['path'] = '/' . $bits['path'];
		}
	}

	// If the URL was protocol-relative, fix scheme and delimiter
	if ( $wasRelative ) {
		$bits['scheme'] = '';
		$bits['delimiter'] = '//';
	}
	return $bits;
}

/**
 * Make URL indexes, appropriate for the el_index field of externallinks.
 *
 * @param $url String
 * @return array
 */
function wfMakeUrlIndexes( $url ) {
	$bits = wfParseUrl( $url );

	// Reverse the labels in the hostname, convert to lower case
	// For emails reverse domainpart only
	if ( $bits['scheme'] == 'mailto' ) {
		$mailparts = explode( '@', $bits['host'], 2 );
		if ( count( $mailparts ) === 2 ) {
			$domainpart = strtolower( implode( '.', array_reverse( explode( '.', $mailparts[1] ) ) ) );
		} else {
			// No domain specified, don't mangle it
			$domainpart = '';
		}
		$reversedHost = $domainpart . '@' . $mailparts[0];
	} else {
		$reversedHost = strtolower( implode( '.', array_reverse( explode( '.', $bits['host'] ) ) ) );
	}
	// Add an extra dot to the end
	// Why? Is it in wrong place in mailto links?
	if ( substr( $reversedHost, -1, 1 ) !== '.' ) {
		$reversedHost .= '.';
	}
	// Reconstruct the pseudo-URL
	$prot = $bits['scheme'];
	$index = $prot . $bits['delimiter'] . $reversedHost;
	// Leave out user and password. Add the port, path, query and fragment
	if ( isset( $bits['port'] ) ) {
		$index .= ':' . $bits['port'];
	}
	if ( isset( $bits['path'] ) ) {
		$index .= $bits['path'];
	} else {
		$index .= '/';
	}
	if ( isset( $bits['query'] ) ) {
		$index .= '?' . $bits['query'];
	}
	if ( isset( $bits['fragment'] ) ) {
		$index .= '#' . $bits['fragment'];
	}

	if ( $prot == '' ) {
		return array( "http:$index", "https:$index" );
	} else {
		return array( $index );
	}
}

/**
 * Check whether a given URL has a domain that occurs in a given set of domains
 * @param $url string URL
 * @param $domains array Array of domains (strings)
 * @return bool True if the host part of $url ends in one of the strings in $domains
 */
function wfMatchesDomainList( $url, $domains ) {
	$bits = wfParseUrl( $url );
	if ( is_array( $bits ) && isset( $bits['host'] ) ) {
		foreach ( (array)$domains as $domain ) {
			// FIXME: This gives false positives. http://nds-nl.wikipedia.org will match nl.wikipedia.org
			// We should use something that interprets dots instead
			if ( substr( $bits['host'], -strlen( $domain ) ) === $domain ) {
				return true;
			}
		}
	}
	return false;
}

/**
 * Sends a line to the debug log if enabled or, optionally, to a comment in output.
 * In normal operation this is a NOP.
 *
 * Controlling globals:
 * $wgDebugLogFile - points to the log file
 * $wgProfileOnly - if set, normal debug messages will not be recorded.
 * $wgDebugRawPage - if false, 'action=raw' hits will not result in debug output.
 * $wgDebugComments - if on, some debug items may appear in comments in the HTML output.
 *
 * @param $text String
 * @param $logonly Bool: set true to avoid appearing in HTML when $wgDebugComments is set
 */
function wfDebug( $text, $logonly = false ) {
	global $wgOut, $wgDebugLogFile, $wgDebugComments, $wgProfileOnly, $wgDebugRawPage;
	global $wgDebugLogPrefix, $wgShowDebug;

	static $cache = array(); // Cache of unoutputted messages
	$text = wfDebugTimer() . $text;

	if ( !$wgDebugRawPage && wfIsDebugRawPage() ) {
		return;
	}

	if ( ( $wgDebugComments || $wgShowDebug ) && !$logonly ) {
		$cache[] = $text;

		if ( isset( $wgOut ) && is_object( $wgOut ) ) {
			// add the message and any cached messages to the output
			array_map( array( $wgOut, 'debug' ), $cache );
			$cache = array();
		}
	}
	# if ( wfRunHooks( 'Debug', array( $text, null ) ) ) { // Wikia change - BAC-91
		if ( $wgDebugLogFile != '' && !$wgProfileOnly ) {
			# Strip unprintables; they can switch terminal modes when binary data
			# gets dumped, which is pretty annoying.
			$text = preg_replace( '![\x00-\x08\x0b\x0c\x0e-\x1f]!', ' ', $text );
			$text = $wgDebugLogPrefix . $text;
			wfErrorLog( $text, $wgDebugLogFile );
		}
	#} // Wikia change - BAC-91

	MWDebug::debugMsg( $text );
}

/**
 * Returns true if debug logging should be suppressed if $wgDebugRawPage = false
 */
function wfIsDebugRawPage() {
	static $cache;
	if ( $cache !== null ) {
		return $cache;
	}
	# Check for raw action using $_GET not $wgRequest, since the latter might not be initialised yet
	if ( ( isset( $_GET['action'] ) && $_GET['action'] == 'raw' )
		|| (
			isset( $_SERVER['SCRIPT_NAME'] )
			&& substr( $_SERVER['SCRIPT_NAME'], -8 ) == 'load.php'
		) )
	{
		$cache = true;
	} else {
		$cache = false;
	}
	return $cache;
}

/**
 * Get microsecond timestamps for debug logs
 *
 * @return string
 */
function wfDebugTimer() {
	global $wgDebugTimestamps, $wgRequestTime;

	if ( !$wgDebugTimestamps ) {
		return '';
	}

	$prefix = sprintf( "%6.4f", microtime( true ) - $wgRequestTime );
	$mem = sprintf( "%5.1fM", ( memory_get_usage( true ) / ( 1024 * 1024 ) ) );
	return "$prefix $mem  ";
}

/**
 * Send a line giving PHP memory usage.
 *
 * @param $exact Bool: print exact values instead of kilobytes (default: false)
 */
function wfDebugMem( $exact = false ) {
	$mem = memory_get_usage();
	if( !$exact ) {
		$mem = floor( $mem / 1024 ) . ' kilobytes';
	} else {
		$mem .= ' bytes';
	}
	wfDebug( "Memory usage: $mem\n" );
}

/**
 * Send a line to a supplementary debug log file, if configured, or main debug log if not.
 * $wgDebugLogGroups[$logGroup] should be set to a filename to send to a separate log.
 *
 * @param $logGroup String
 * @param $text String
 * @param $public Bool: whether to log the event in the public log if no private
 *                     log file is specified, (default true)
 */
function wfDebugLog( $logGroup, $text, $public = true ) {
	global $wgDebugLogGroups;
	$text = trim( $text ) . "\n";
	if( isset( $wgDebugLogGroups[$logGroup] ) ) {
		$time = wfTimestamp( TS_DB );
		$wiki = wfWikiID();
		$host = wfHostname();
		if ( wfRunHooks( 'Debug', array( $text, $logGroup ) ) ) {
			wfErrorLog( "$time $host $wiki: $text", $wgDebugLogGroups[$logGroup] );
		}
	} elseif ( $public === true ) {
		wfDebug( $text, true );
	}
}

/**
 * Log for database errors
 *
 * @param $text String: database error message.
 */
function wfLogDBError( $text ) {
	global $wgDBerrorLog;
	if ( $wgDBerrorLog ) {
		$host = wfHostname();
		$wiki = wfWikiID();
		$text = date( 'D M j G:i:s T Y' ) . "\t$host\t$wiki\t$text";
		wfErrorLog( $text, $wgDBerrorLog );
	}
}

/**
 * Throws a warning that $function is deprecated
 *
 * @param $function String
 * @param $version String|false: Added in 1.19.
 * @param $component String|false: Added in 1.19.
 *
 * @return null
 */
function wfDeprecated( $function, $version = false, $component = false ) {
	static $functionsWarned = array();

	MWDebug::deprecated( $function, $version, $component );

	if ( !isset( $functionsWarned[$function] ) ) {
		$functionsWarned[$function] = true;

		if ( $version ) {
			global $wgDeprecationReleaseLimit;

			if ( $wgDeprecationReleaseLimit && $component === false ) {
				# Strip -* off the end of $version so that branches can use the
				# format #.##-branchname to avoid issues if the branch is merged into
				# a version of MediaWiki later than what it was branched from
				$comparableVersion = preg_replace( '/-.*$/', '', $version );

				# If the comparableVersion is larger than our release limit then
				# skip the warning message for the deprecation
				if ( version_compare( $wgDeprecationReleaseLimit, $comparableVersion, '<' ) ) {
					return;
				}
			}

			$component = $component === false ? 'MediaWiki' : $component;
			wfWarn( "Use of $function was deprecated in $component $version.", 2 );
		} else {
			wfWarn( "Use of $function is deprecated.", 2 );
		}
	}
}

/**
 * Send a warning either to the debug log or in a PHP error depending on
 * $wgDevelopmentWarnings
 *
 * @param $msg String: message to send
 * @param $callerOffset Integer: number of items to go back in the backtrace to
 *        find the correct caller (1 = function calling wfWarn, ...)
 * @param $level Integer: PHP error level; only used when $wgDevelopmentWarnings
 *        is true
 */
function wfWarn( $msg, $callerOffset = 1, $level = E_USER_NOTICE ) {
	global $wgDevelopmentWarnings;

	MWDebug::warning( $msg, $callerOffset + 2 );

	$callers = wfDebugBacktrace();
	if ( isset( $callers[$callerOffset + 1] ) ) {
		$callerfunc = $callers[$callerOffset + 1];
		$callerfile = $callers[$callerOffset];
		if ( isset( $callerfile['file'] ) && isset( $callerfile['line'] ) ) {
			$file = $callerfile['file'] . ' at line ' . $callerfile['line'];
		} else {
			$file = '(internal function)';
		}
		$func = '';
		if ( isset( $callerfunc['class'] ) ) {
			$func .= $callerfunc['class'] . '::';
		}
		if ( isset( $callerfunc['function'] ) ) {
			$func .= $callerfunc['function'];
		}
		$msg .= " [Called from $func in $file]";
	}

	if ( $wgDevelopmentWarnings ) {
		trigger_error( $msg, $level );
	} else {
		wfDebug( "$msg\n" );
	}
}

/**
 * Log to a file without getting "file size exceeded" signals.
 *
 * Can also log to TCP or UDP with the syntax udp://host:port/prefix. This will
 * send lines to the specified port, prefixed by the specified prefix and a space.
 *
 * @param $text String
 * @param $file String filename
 */
function wfErrorLog( $text, $file ) {
	if ( substr( $file, 0, 4 ) == 'udp:' ) {
		# Needs the sockets extension
		if ( preg_match( '!^(tcp|udp):(?://)?\[([0-9a-fA-F:]+)\]:(\d+)(?:/(.*))?$!', $file, $m ) ) {
			// IPv6 bracketed host
			$host = $m[2];
			$port = intval( $m[3] );
			$prefix = isset( $m[4] ) ? $m[4] : false;
			$domain = AF_INET6;
		} elseif ( preg_match( '!^(tcp|udp):(?://)?([a-zA-Z0-9.-]+):(\d+)(?:/(.*))?$!', $file, $m ) ) {
			$host = $m[2];
			if ( !IP::isIPv4( $host ) ) {
				$host = gethostbyname( $host );
			}
			$port = intval( $m[3] );
			$prefix = isset( $m[4] ) ? $m[4] : false;
			$domain = AF_INET;
		} else {
			throw new MWException( __METHOD__ . ': Invalid UDP specification' );
		}

		// Clean it up for the multiplexer
		if ( strval( $prefix ) !== '' ) {
			$text = preg_replace( '/^/m', $prefix . ' ', $text );

			// Limit to 64KB
			if ( strlen( $text ) > 65506 ) {
				$text = substr( $text, 0, 65506 );
			}

			if ( substr( $text, -1 ) != "\n" ) {
				$text .= "\n";
			}
		} elseif ( strlen( $text ) > 65507 ) {
			$text = substr( $text, 0, 65507 );
		}

		$sock = socket_create( $domain, SOCK_DGRAM, SOL_UDP );
		if ( !$sock ) {
			return;
		}

		socket_sendto( $sock, $text, strlen( $text ), 0, $host, $port );
		socket_close( $sock );
	} else {
		wfSuppressWarnings();
		$exists = file_exists( $file );
		$size = $exists ? filesize( $file ) : false;
		if ( !$exists || ( $size !== false && $size + strlen( $text ) < 0x7fffffff ) ) {
			file_put_contents( $file, $text, FILE_APPEND );
		}
		wfRestoreWarnings();
	}
}

/**
 * @todo document
 */
function wfLogProfilingData() {
	global $wgRequestTime, $wgDebugLogFile, $wgDebugRawPage, $wgRequest;
	global $wgProfileLimit, $wgUser, $wgProfilingDataLogged;

	$wgProfilingDataLogged = true;

	$profiler = Profiler::instance();

	# Profiling must actually be enabled...
	if ( $profiler->isStub() ) {
		return;
	}

	// Get total page request time and only show pages that longer than
	// $wgProfileLimit time (default is 0)
	$elapsed = microtime( true ) - $wgRequestTime;
	if ( $elapsed <= $wgProfileLimit ) {
		return;
	}

	$profiler->logData();

	// Check whether this should be logged in the debug file.
	if ( $wgDebugLogFile == '' || ( !$wgDebugRawPage && wfIsDebugRawPage() ) ) {
		return;
	}

	$forward = '';
	if ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$forward = ' forwarded for ' . $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	if ( !empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$forward .= ' client IP ' . $_SERVER['HTTP_CLIENT_IP'];
	}
	if ( !empty( $_SERVER['HTTP_FROM'] ) ) {
		$forward .= ' from ' . $_SERVER['HTTP_FROM'];
	}
	if ( $forward ) {
		$forward = "\t(proxied via {$_SERVER['REMOTE_ADDR']}{$forward})";
	}
	// Don't load $wgUser at this late stage just for statistics purposes
	// @todo FIXME: We can detect some anons even if it is not loaded. See User::getId()
	if ( $wgUser->isItemLoaded( 'id' ) && $wgUser->isAnon() ) {
		$forward .= ' anon';
	}

	// Wikia change - begin - FauxRequest::getRequestURL() is not implemented and throws exception
	// in maintenance scripts
	try {
		$log = sprintf( "%s\t%04.3f\t%s\n",
			gmdate( 'YmdHis' ), $elapsed,
			urldecode( $wgRequest->getRequestURL() . $forward ) );

		wfErrorLog( $log . $profiler->getOutput(), $wgDebugLogFile );
	} catch (MWException $e) {
		// double-check it is the case
		if ( $e->getMessage() !== "FauxRequest::getRequestURL() not implemented" ) {
			throw $e;
		}
	}
	// Wikia change - end
}

/**
 * Check if the wiki read-only lock file is present. This can be used to lock
 * off editing functions, but doesn't guarantee that the database will not be
 * modified.
 *
 * @return bool
 */
function wfReadOnly() {
	global $wgReadOnlyFile, $wgReadOnly;

	// <Wikia>
	global $wgDBReadOnly;
	$wgDBReadOnly = (bool)$wgReadOnly;
	// </Wikia>
	if ( !is_null( $wgReadOnly ) ) {
		return (bool)$wgReadOnly;
	}
	if ( $wgReadOnlyFile == '' ) {
		return false;
	}
	// Set $wgReadOnly for faster access next time
	/**
	if ( is_file( $wgReadOnlyFile ) ) {
		$wgReadOnly = file_get_contents( $wgReadOnlyFile );
	} else {
		$wgReadOnly = false;
	}
	**/
	return (bool)$wgReadOnly;
}

/**
 * @return bool
 */
function wfReadOnlyReason() {
	global $wgReadOnly;
	wfReadOnly();
	return $wgReadOnly;
}

/**
 * Return a Language object from $langcode
 *
 * @param $langcode Mixed: either:
 *                  - a Language object
 *                  - code of the language to get the message for, if it is
 *                    a valid code create a language for that language, if
 *                    it is a string but not a valid code then make a basic
 *                    language object
 *                  - a boolean: if it's false then use the global object for
 *                    the current user's language (as a fallback for the old parameter
 *                    functionality), or if it is true then use global object
 *                    for the wiki's content language.
 * @return Language object
 */
function wfGetLangObj( $langcode = false ) {
	# Identify which language to get or create a language object for.
	# Using is_object here due to Stub objects.
	if( is_object( $langcode ) ) {
		# Great, we already have the object (hopefully)!
		return $langcode;
	}

	global $wgContLang, $wgLanguageCode;
	if( $langcode === true || $langcode === $wgLanguageCode ) {
		# $langcode is the language code of the wikis content language object.
		# or it is a boolean and value is true
		return $wgContLang;
	}

	global $wgLang;
	if( $langcode === false || $langcode === $wgLang->getCode() ) {
		# $langcode is the language code of user language object.
		# or it was a boolean and value is false
		return $wgLang;
	}

	$validCodes = array_keys( Language::getLanguageNames() );
	if( in_array( $langcode, $validCodes ) ) {
		# $langcode corresponds to a valid language.
		return Language::factory( $langcode );
	}

	# $langcode is a string, but not a valid language code; use content language.
	wfDebug( "Invalid language code passed to wfGetLangObj, falling back to content language.\n" );
	return $wgContLang;
}

/**
 * Old function when $wgBetterDirectionality existed
 * Removed in core, kept in extensions for backwards compat.
 *
 * @deprecated since 1.18
 * @return Language
 */
function wfUILang() {
	wfDeprecated( __METHOD__, '1.18' );
	global $wgLang;
	return $wgLang;
}

/**
 * This is the new function for getting translated interface messages.
 * See the Message class for documentation how to use them.
 * The intention is that this function replaces all old wfMsg* functions.
 * @param $key \string Message key.
 * Varargs: normal message parameters.
 * @return Message
 * @since 1.17
 */
function wfMessage( $key /*...*/) {
	$params = func_get_args();
	array_shift( $params );
	if ( isset( $params[0] ) && is_array( $params[0] ) ) {
		$params = $params[0];
	}
	return new Message( $key, $params );
}

/**
 * This function accepts multiple message keys and returns a message instance
 * for the first message which is non-empty. If all messages are empty then an
 * instance of the first message key is returned.
 * @param varargs: message keys
 * @return Message
 * @since 1.18
 */
function wfMessageFallback( /*...*/ ) {
	$args = func_get_args();
	return MWFunction::callArray( 'Message::newFallbackSequence', $args );
}

/**
 * @deprecated since 1.18
 *
 * Get a message from anywhere, for the current user language.
 *
 * Use wfMsgForContent() instead if the message should NOT
 * change depending on the user preferences.
 *
 * @param $key String: lookup key for the message, usually
 *    defined in languages/Language.php
 *
 * Parameters to the message, which can be used to insert variable text into
 * it, can be passed to this function in the following formats:
 * - One per argument, starting at the second parameter
 * - As an array in the second parameter
 * These are not shown in the function definition.
 *
 * @return String
 */
function wfMsg( $key ) {
	wfDeprecated( __METHOD__, '1.18' );
	$args = func_get_args();
	array_shift( $args );
	return wfMsgReal( $key, $args );
}

/**
 * @deprecated since 1.18
 *
 * Same as above except doesn't transform the message
 *
 * @param $key String
 * @return String
 */
function wfMsgNoTrans( $key ) {
	wfDeprecated( __METHOD__, '1.18' );
	$args = func_get_args();
	array_shift( $args );
	return wfMsgReal( $key, $args, true, false, false );
}

/**
 * @deprecated since 1.18
 *
 * Get a message from anywhere, for the current global language
 * set with $wgLanguageCode.
 *
 * Use this if the message should NOT change dependent on the
 * language set in the user's preferences. This is the case for
 * most text written into logs, as well as link targets (such as
 * the name of the copyright policy page). Link titles, on the
 * other hand, should be shown in the UI language.
 *
 * Note that MediaWiki allows users to change the user interface
 * language in their preferences, but a single installation
 * typically only contains content in one language.
 *
 * Be wary of this distinction: If you use wfMsg() where you should
 * use wfMsgForContent(), a user of the software may have to
 * customize potentially hundreds of messages in
 * order to, e.g., fix a link in every possible language.
 *
 * @param $key String: lookup key for the message, usually
 *     defined in languages/Language.php
 * @return String
 */
function wfMsgForContent( $key ) {
	wfDeprecated( __METHOD__, '1.18' );

	global $wgForceUIMsgAsContentMsg;
	$args = func_get_args();
	array_shift( $args );
	$forcontent = true;
	if( is_array( $wgForceUIMsgAsContentMsg ) &&
		in_array( $key, $wgForceUIMsgAsContentMsg ) )
	{
		$forcontent = false;
	}
	return wfMsgReal( $key, $args, true, $forcontent );
}

/**
 * @deprecated since 1.18
 *
 * Same as above except doesn't transform the message
 *
 * @param $key String
 * @return String
 */
function wfMsgForContentNoTrans( $key ) {
	wfDeprecated( __METHOD__, '1.18' );

	global $wgForceUIMsgAsContentMsg;
	$args = func_get_args();
	array_shift( $args );
	$forcontent = true;
	if( is_array( $wgForceUIMsgAsContentMsg ) &&
		in_array( $key, $wgForceUIMsgAsContentMsg ) )
	{
		$forcontent = false;
	}
	return wfMsgReal( $key, $args, true, $forcontent, false );
}

/**
 * @deprecated since 1.18
 *
 * Really get a message
 *
 * @param $key String: key to get.
 * @param $args
 * @param $useDB Boolean
 * @param $forContent Mixed: Language code, or false for user lang, true for content lang.
 * @param $transform Boolean: Whether or not to transform the message.
 * @return String: the requested message.
 */
function wfMsgReal( $key, $args, $useDB = true, $forContent = false, $transform = true ) {
	wfProfileIn( __METHOD__ );
	wfDeprecated( __METHOD__, '1.18' );
	$message = wfMsgGetKey( $key, $useDB, $forContent, $transform );
	$message = wfMsgReplaceArgs( $message, $args );
	wfProfileOut( __METHOD__ );
	return $message;
}

/**
 * @deprecated since 1.18
 *
 * Fetch a message string value, but don't replace any keys yet.
 *
 * @param $key String
 * @param $useDB Bool
 * @param $langCode String: Code of the language to get the message for, or
 *                  behaves as a content language switch if it is a boolean.
 * @param $transform Boolean: whether to parse magic words, etc.
 * @return string
 */
function wfMsgGetKey( $key, $useDB = true, $langCode = false, $transform = true ) {
	wfDeprecated( __METHOD__, '1.18' );

	wfRunHooks( 'NormalizeMessageKey', array( &$key, &$useDB, &$langCode, &$transform ) );

	$cache = MessageCache::singleton();
	$message = $cache->get( $key, $useDB, $langCode );
	if( $message === false ) {
		$message = '&lt;' . htmlspecialchars( $key ) . '&gt;';
	} elseif ( $transform ) {
		$message = $cache->transform( $message );
	}
	return $message;
}

/**
 * Replace message parameter keys on the given formatted output.
 *
 * @param $message String
 * @param $args Array
 * @return string
 * @private
 */
function wfMsgReplaceArgs( $message, $args ) {
	# Fix windows line-endings
	# Some messages are split with explode("\n", $msg)
	$message = str_replace( "\r", '', $message );

	// Replace arguments
	if ( count( $args ) ) {
		if ( is_array( $args[0] ) ) {
			$args = array_values( $args[0] );
		}
		$replacementKeys = array();
		foreach( $args as $n => $param ) {
			$replacementKeys['$' . ( $n + 1 )] = $param;
		}
		$message = strtr( $message, $replacementKeys );
	}

	return $message;
}

/**
 * @deprecated since 1.18
 *
 * Return an HTML-escaped version of a message.
 * Parameter replacements, if any, are done *after* the HTML-escaping,
 * so parameters may contain HTML (eg links or form controls). Be sure
 * to pre-escape them if you really do want plaintext, or just wrap
 * the whole thing in htmlspecialchars().
 *
 * @param $key String
 * @param string ... parameters
 * @return string
 */
function wfMsgHtml( $key ) {
	wfDeprecated( __METHOD__, '1.18' );
	$args = func_get_args();
	array_shift( $args );
	return wfMsgReplaceArgs( htmlspecialchars( wfMsgGetKey( $key ) ), $args );
}

/**
 * @deprecated since 1.18
 *
 * Return an HTML version of message
 * Parameter replacements, if any, are done *after* parsing the wiki-text message,
 * so parameters may contain HTML (eg links or form controls). Be sure
 * to pre-escape them if you really do want plaintext, or just wrap
 * the whole thing in htmlspecialchars().
 *
 * @param $key String
 * @param string ... parameters
 * @return string
 */
function wfMsgWikiHtml( $key ) {
	wfDeprecated( __METHOD__, '1.18' );
	$args = func_get_args();
	array_shift( $args );
	return wfMsgReplaceArgs(
		MessageCache::singleton()->parse( wfMsgGetKey( $key ), null,
		/* can't be set to false */ true, /* interface */ true )->getText(),
		$args );
}

/**
 * @deprecated since 1.18
 *
 * Returns message in the requested format
 * @param $key String: key of the message
 * @param $options Array: processing rules. Can take the following options:
 *   <i>parse</i>: parses wikitext to HTML
 *   <i>parseinline</i>: parses wikitext to HTML and removes the surrounding
 *       p's added by parser or tidy
 *   <i>escape</i>: filters message through htmlspecialchars
 *   <i>escapenoentities</i>: same, but allows entity references like &#160; through
 *   <i>replaceafter</i>: parameters are substituted after parsing or escaping
 *   <i>parsemag</i>: transform the message using magic phrases
 *   <i>content</i>: fetch message for content language instead of interface
 * Also can accept a single associative argument, of the form 'language' => 'xx':
 *   <i>language</i>: Language object or language code to fetch message for
 *       (overriden by <i>content</i>).
 * Behavior for conflicting options (e.g., parse+parseinline) is undefined.
 *
 * @return String
 */
function wfMsgExt( $key, $options ) {
	wfProfileIn(__METHOD__);
	wfDeprecated( __METHOD__, '1.18' );
	$args = func_get_args();
	array_shift( $args );
	array_shift( $args );
	$options = (array)$options;

	foreach( $options as $arrayKey => $option ) {
		if( !preg_match( '/^[0-9]+|language$/', $arrayKey ) ) {
			# An unknown index, neither numeric nor "language"
			wfWarn( "wfMsgExt called with incorrect parameter key $arrayKey", 1, E_USER_WARNING );
		} elseif( preg_match( '/^[0-9]+$/', $arrayKey ) && !in_array( $option,
		array( 'parse', 'parseinline', 'escape', 'escapenoentities',
		'replaceafter', 'parsemag', 'content' ) ) ) {
			# A numeric index with unknown value
			wfWarn( "wfMsgExt called with incorrect parameter $option", 1, E_USER_WARNING );
		}
	}

	if( in_array( 'content', $options, true ) ) {
		$forContent = true;
		$langCode = true;
		$langCodeObj = null;
	} elseif( array_key_exists( 'language', $options ) ) {
		$forContent = false;
		$langCode = wfGetLangObj( $options['language'] );
		$langCodeObj = $langCode;
	} else {
		$forContent = false;
		$langCode = false;
		$langCodeObj = null;
	}

	$string = wfMsgGetKey( $key, /*DB*/true, $langCode, /*Transform*/false );

	if( !in_array( 'replaceafter', $options, true ) ) {
		$string = wfMsgReplaceArgs( $string, $args );
	}

	$messageCache = MessageCache::singleton();
	$parseInline = in_array( 'parseinline', $options, true );
	if( in_array( 'parse', $options, true ) || $parseInline ) {
		$string = $messageCache->parse( $string, null, true, !$forContent, $langCodeObj );
		if ( $string instanceof ParserOutput ) {
			$string = $string->getText();
		}

		if ( $parseInline ) {
			$m = array();
			if( preg_match( '/^<p>(.*)\n?<\/p>\n?$/sU', $string, $m ) ) {
				$string = $m[1];
			}
		}
	} elseif ( in_array( 'parsemag', $options, true ) ) {
		$string = $messageCache->transform( $string,
				!$forContent, $langCodeObj );
	}

	if ( in_array( 'escape', $options, true ) ) {
		$string = htmlspecialchars ( $string );
	} elseif ( in_array( 'escapenoentities', $options, true ) ) {
		$string = Sanitizer::escapeHtmlAllowEntities( $string );
	}

	if( in_array( 'replaceafter', $options, true ) ) {
		$string = wfMsgReplaceArgs( $string, $args );
	}
	wfProfileOut(__METHOD__);

	return $string;
}

/**
 * Since wfMsg() and co suck, they don't return false if the message key they
 * looked up didn't exist but a XHTML string, this function checks for the
 * nonexistance of messages by checking the MessageCache::get() result directly.
 *
 * @param $key      String: the message key looked up
 * @return Boolean True if the message *doesn't* exist.
 */
function wfEmptyMsg( $key ) {
	return MessageCache::singleton()->get( $key, /*useDB*/true, /*content*/false ) === false;
}

/**
 * Throw a debugging exception. This function previously once exited the process,
 * but now throws an exception instead, with similar results.
 *
 * @param $msg String: message shown when dying.
 */
function wfDebugDieBacktrace( $msg = '' ) {
	throw new MWException( $msg );
}

/**
 * Fetch server name for use in error reporting etc.
 * Use real server name if available, so we know which machine
 * in a server farm generated the current page.
 *
 * @return string
 */
function wfHostname() {
	static $host;
	if ( is_null( $host ) ) {
		if ( function_exists( 'posix_uname' ) ) {
			// This function not present on Windows
			$uname = posix_uname();
		} else {
			$uname = false;
		}
		if( is_array( $uname ) && isset( $uname['nodename'] ) ) {
			$host = $uname['nodename'];
		} elseif ( getenv( 'COMPUTERNAME' ) ) {
			# Windows computer name
			$host = getenv( 'COMPUTERNAME' );
		} else {
			# This may be a virtual server.
			$host = $_SERVER['SERVER_NAME'];
		}
	}
	return $host;
}

/**
 * Returns a HTML comment with the elapsed time since request.
 * This method has no side effects.
 *
 * @return string
 */
function wfReportTime() {
	global $wgRequestTime, $wgShowHostnames;

	// Wikia change - begin
	// @author macbre - BAC-550
	global $wgDisableReportTime;
	if ( !empty( $wgDisableReportTime ) ) {
		return '';
	}
	// Wikia change - end

	$elapsed = microtime( true ) - $wgRequestTime;

	return $wgShowHostnames
		? sprintf( '<!-- Served by %s in %01.3f secs. -->', wfHostname(), $elapsed )
		: sprintf( '<!-- Served in %01.3f secs. -->', $elapsed );
}

/**
 * Safety wrapper for debug_backtrace().
 *
 * With Zend Optimizer 3.2.0 loaded, this causes segfaults under somewhat
 * murky circumstances, which may be triggered in part by stub objects
 * or other fancy talkin'.
 *
 * Will return an empty array if Zend Optimizer is detected or if
 * debug_backtrace is disabled, otherwise the output from
 * debug_backtrace() (trimmed).
 *
 * @param $limit int This parameter can be used to limit the number of stack frames returned
 *
 * @return array of backtrace information
 */
function wfDebugBacktrace( $limit = 0 ) {
	static $disabled = null;

	if( extension_loaded( 'Zend Optimizer' ) ) {
		wfDebug( "Zend Optimizer detected; skipping debug_backtrace for safety.\n" );
		return array();
	}

	if ( is_null( $disabled ) ) {
		$disabled = false;
		$functions = explode( ',', ini_get( 'disable_functions' ) );
		$functions = array_map( 'trim', $functions );
		$functions = array_map( 'strtolower', $functions );
		if ( in_array( 'debug_backtrace', $functions ) ) {
			wfDebug( "debug_backtrace is in disabled_functions\n" );
			$disabled = true;
		}
	}
	if ( $disabled ) {
		return array();
	}

	if ( $limit && version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
		return array_slice( debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, $limit ), 1 );
	} else {
		return array_slice( debug_backtrace(), 1 );
	}
}

/**
 * Get a debug backtrace as a string
 *
 * @return string
 */
function wfBacktrace( $forceCommandLineMode = false ) {
	global $wgCommandLineMode;

	$commandLinemode = $wgCommandLineMode || $forceCommandLineMode;
	if ( $commandLinemode ) {
		$msg = '';
	} else {
		$msg = "<ul>\n";
	}
	$backtrace = wfDebugBacktrace();
	foreach( $backtrace as $call ) {
		if( isset( $call['file'] ) ) {
			$f = explode( DIRECTORY_SEPARATOR, $call['file'] );
			$file = $f[count( $f ) - 1];
		} else {
			$file = '-';
		}
		if( isset( $call['line'] ) ) {
			$line = $call['line'];
		} else {
			$line = '-';
		}
		if ( $commandLinemode ) {
			$msg .= "$file line $line calls ";
		} else {
			$msg .= '<li>' . $file . ' line ' . $line . ' calls ';
		}
		if( !empty( $call['class'] ) ) {
			$msg .= $call['class'] . $call['type'];
		}
		$msg .= $call['function'] . '()';

		if ( $commandLinemode ) {
			$msg .= "\n";
		} else {
			$msg .= "</li>\n";
		}
	}
	if ( $commandLinemode ) {
		$msg .= "\n";
	} else {
		$msg .= "</ul>\n";
	}

	return $msg;
}

/**
 * Get the name of the function which called this function
 *
 * @param $level Int
 * @return Bool|string
 */
function wfGetCaller( $level = 2 ) {
	$backtrace = wfDebugBacktrace( $level );
	if ( isset( $backtrace[$level] ) ) {
		return wfFormatStackFrame( $backtrace[$level] );
	} else {
		$caller = 'unknown';
	}
	return $caller;
}

/**
 * Return a string consisting of callers in the stack. Useful sometimes
 * for profiling specific points.
 *
 * @param $limit The maximum depth of the stack frame to return, or false for
 *               the entire stack.
 * @return String
 */
function wfGetAllCallers( $limit = 3 ) {
	$trace = array_reverse( wfDebugBacktrace() );
	if ( !$limit || $limit > count( $trace ) - 1 ) {
		$limit = count( $trace ) - 1;
	}
	$trace = array_slice( $trace, -$limit - 1, $limit );
	return implode( '/', array_map( 'wfFormatStackFrame', $trace ) );
}

/**
 * Return a string representation of frame
 *
 * @param $frame Array
 * @return Bool
 */
function wfFormatStackFrame( $frame ) {
	return isset( $frame['class'] ) ?
		$frame['class'] . '::' . $frame['function'] :
		$frame['function'];
}


/* Some generic result counters, pulled out of SearchEngine */


/**
 * @todo document
 *
 * @param $offset Int
 * @param $limit Int
 * @return String
 */
function wfShowingResults( $offset, $limit ) {
	global $wgLang;
	return wfMsgExt(
		'showingresults',
		array( 'parseinline' ),
		$wgLang->formatNum( $limit ),
		$wgLang->formatNum( $offset + 1 )
	);
}

/**
 * Generate (prev x| next x) (20|50|100...) type links for paging
 *
 * @param $offset String
 * @param $limit Integer
 * @param $link String
 * @param $query String: optional URL query parameter string
 * @param $atend Bool: optional param for specified if this is the last page
 * @return String
 * @deprecated in 1.19; use Language::viewPrevNext() instead
 */
function wfViewPrevNext( $offset, $limit, $link, $query = '', $atend = false ) {
	wfDeprecated( __METHOD__, '1.19' );

	global $wgLang;

	$query = wfCgiToArray( $query );

	if( is_object( $link ) ) {
		$title = $link;
	} else {
		$title = Title::newFromText( $link );
		if( is_null( $title ) ) {
			return false;
		}
	}

	return $wgLang->viewPrevNext( $title, $offset, $limit, $query, $atend );
}

/**
 * Make a list item, used by various special pages
 *
 * @param $page String Page link
 * @param $details String Text between brackets
 * @param $oppositedm Boolean	Add the direction mark opposite to your
 *								language, to display text properly
 * @return String
 * @deprecated since 1.19; use Language::specialList() instead
 */
function wfSpecialList( $page, $details, $oppositedm = true ) {
	global $wgLang;
	return $wgLang->specialList( $page, $details, $oppositedm );
}

/**
 * @todo document
 * @todo FIXME: We may want to blacklist some broken browsers
 *
 * @param $force Bool
 * @return bool Whereas client accept gzip compression
 */
function wfClientAcceptsGzip( $force = false ) {
	static $result = null;
	if ( $result === null || $force ) {
		$result = false;
		if( isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) ) {
			# @todo FIXME: We may want to blacklist some broken browsers
			$m = array();
			if( preg_match(
				'/\bgzip(?:;(q)=([0-9]+(?:\.[0-9]+)))?\b/',
				$_SERVER['HTTP_ACCEPT_ENCODING'],
				$m )
			)
			{
				if( isset( $m[2] ) && ( $m[1] == 'q' ) && ( $m[2] == 0 ) ) {
					$result = false;
					return $result;
				}
				wfDebug( "wfClientAcceptsGzip: client accepts gzip.\n" );
				$result = true;
			}
		}
	}
	return $result;
}

/**
 * Obtain the offset and limit values from the request string;
 * used in special pages
 *
 * @param $deflimit Int default limit if none supplied
 * @param $optionname String Name of a user preference to check against
 * @return array
 *
 */
function wfCheckLimits( $deflimit = 50, $optionname = 'rclimit' ) {
	global $wgRequest;
	return $wgRequest->getLimitOffset( $deflimit, $optionname );
}

/**
 * Escapes the given text so that it may be output using addWikiText()
 * without any linking, formatting, etc. making its way through. This
 * is achieved by substituting certain characters with HTML entities.
 * As required by the callers, <nowiki> is not used.
 *
 * @param $text String: text to be escaped
 * @return String
 */
function wfEscapeWikiText( $text ) {
	$text = strtr( "\n$text", array(
		'"' => '&#34;', '&' => '&#38;', "'" => '&#39;', '<' => '&#60;',
		'=' => '&#61;', '>' => '&#62;', '[' => '&#91;', ']' => '&#93;',
		'{' => '&#123;', '|' => '&#124;', '}' => '&#125;',
		"\n#" => "\n&#35;", "\n*" => "\n&#42;",
		"\n:" => "\n&#58;", "\n;" => "\n&#59;",
		'://' => '&#58;//', 'ISBN ' => 'ISBN&#32;', 'RFC ' => 'RFC&#32;',
	) );
	return substr( $text, 1 );
}

/**
 * Get the current unix timetstamp with microseconds.  Useful for profiling
 * @return Float
 */
function wfTime() {
	return microtime( true );
}

/**
 * Sets dest to source and returns the original value of dest
 * If source is NULL, it just returns the value, it doesn't set the variable
 * If force is true, it will set the value even if source is NULL
 *
 * @param $dest Mixed
 * @param $source Mixed
 * @param $force Bool
 * @return Mixed
 */
function wfSetVar( &$dest, $source, $force = false ) {
	$temp = $dest;
	if ( !is_null( $source ) || $force ) {
		$dest = $source;
	}
	return $temp;
}

/**
 * As for wfSetVar except setting a bit
 *
 * @param $dest Int
 * @param $bit Int
 * @param $state Bool
 *
 * @return bool
 */
function wfSetBit( &$dest, $bit, $state = true ) {
	$temp = (bool)( $dest & $bit );
	if ( !is_null( $state ) ) {
		if ( $state ) {
			$dest |= $bit;
		} else {
			$dest &= ~$bit;
		}
	}
	return $temp;
}

/**
 * A wrapper around the PHP function var_export().
 * Either print it or add it to the regular output ($wgOut).
 *
 * @param $var A PHP variable to dump.
 */
function wfVarDump( $var ) {
	global $wgOut;
	$s = str_replace( "\n", "<br />\n", var_export( $var, true ) . "\n" );
	if ( headers_sent() || !isset( $wgOut ) || !is_object( $wgOut ) ) {
		print $s;
	} else {
		$wgOut->addHTML( $s );
	}
}

/**
 * Provide a simple HTTP error.
 *
 * @param $code Int|String
 * @param $label String
 * @param $desc String
 */
function wfHttpError( $code, $label, $desc ) {
	global $wgOut;
	$wgOut->disable();
	header( "HTTP/1.0 $code $label" );
	header( "Status: $code $label" );
	$wgOut->sendCacheControl();

	header( 'Content-type: text/html; charset=utf-8' );
	print "<!doctype html>" .
		'<html><head><title>' .
		htmlspecialchars( $label ) .
		'</title></head><body><h1>' .
		htmlspecialchars( $label ) .
		'</h1><p>' .
		nl2br( htmlspecialchars( $desc ) ) .
		"</p></body></html>\n";
}

/**
 * Clear away any user-level output buffers, discarding contents.
 *
 * Suitable for 'starting afresh', for instance when streaming
 * relatively large amounts of data without buffering, or wanting to
 * output image files without ob_gzhandler's compression.
 *
 * The optional $resetGzipEncoding parameter controls suppression of
 * the Content-Encoding header sent by ob_gzhandler; by default it
 * is left. See comments for wfClearOutputBuffers() for why it would
 * be used.
 *
 * Note that some PHP configuration options may add output buffer
 * layers which cannot be removed; these are left in place.
 *
 * @param $resetGzipEncoding Bool
 */
function wfResetOutputBuffers( $resetGzipEncoding = true ) {
	if( $resetGzipEncoding ) {
		// Suppress Content-Encoding and Content-Length
		// headers from 1.10+s wfOutputHandler
		global $wgDisableOutputCompression;
		$wgDisableOutputCompression = true;
	}
	while( $status = ob_get_status() ) {
		if( $status['type'] == 0 /* PHP_OUTPUT_HANDLER_INTERNAL */ ) {
			// Probably from zlib.output_compression or other
			// PHP-internal setting which can't be removed.
			//
			// Give up, and hope the result doesn't break
			// output behavior.
			break;
		}
		if( !ob_end_clean() ) {
			// Could not remove output buffer handler; abort now
			// to avoid getting in some kind of infinite loop.
			break;
		}
		if( $resetGzipEncoding ) {
			if( $status['name'] == 'ob_gzhandler' ) {
				// Reset the 'Content-Encoding' field set by this handler
				// so we can start fresh.
				if ( function_exists( 'header_remove' ) ) {
					// Available since PHP 5.3.0
					header_remove( 'Content-Encoding' );
				} else {
					// We need to provide a valid content-coding. See bug 28069
					header( 'Content-Encoding: identity' );
				}
				break;
			}
		}
	}
}

/**
 * More legible than passing a 'false' parameter to wfResetOutputBuffers():
 *
 * Clear away output buffers, but keep the Content-Encoding header
 * produced by ob_gzhandler, if any.
 *
 * This should be used for HTTP 304 responses, where you need to
 * preserve the Content-Encoding header of the real result, but
 * also need to suppress the output of ob_gzhandler to keep to spec
 * and avoid breaking Firefox in rare cases where the headers and
 * body are broken over two packets.
 */
function wfClearOutputBuffers() {
	wfResetOutputBuffers( false );
}

/**
 * Converts an Accept-* header into an array mapping string values to quality
 * factors
 *
 * @param $accept String
 * @param $def String default
 * @return Array
 */
function wfAcceptToPrefs( $accept, $def = '*/*' ) {
	# No arg means accept anything (per HTTP spec)
	if( !$accept ) {
		return array( $def => 1.0 );
	}

	$prefs = array();

	$parts = explode( ',', $accept );

	foreach( $parts as $part ) {
		# @todo FIXME: Doesn't deal with params like 'text/html; level=1'
		$values = explode( ';', trim( $part ) );
		$match = array();
		if ( count( $values ) == 1 ) {
			$prefs[$values[0]] = 1.0;
		} elseif ( preg_match( '/q\s*=\s*(\d*\.\d+)/', $values[1], $match ) ) {
			$prefs[$values[0]] = floatval( $match[1] );
		}
	}

	return $prefs;
}

/**
 * Checks if a given MIME type matches any of the keys in the given
 * array. Basic wildcards are accepted in the array keys.
 *
 * Returns the matching MIME type (or wildcard) if a match, otherwise
 * NULL if no match.
 *
 * @param $type String
 * @param $avail Array
 * @return string
 * @private
 */
function mimeTypeMatch( $type, $avail ) {
	if( array_key_exists( $type, $avail ) ) {
		return $type;
	} else {
		$parts = explode( '/', $type );
		if( array_key_exists( $parts[0] . '/*', $avail ) ) {
			return $parts[0] . '/*';
		} elseif( array_key_exists( '*/*', $avail ) ) {
			return '*/*';
		} else {
			return null;
		}
	}
}

/**
 * Returns the 'best' match between a client's requested internet media types
 * and the server's list of available types. Each list should be an associative
 * array of type to preference (preference is a float between 0.0 and 1.0).
 * Wildcards in the types are acceptable.
 *
 * @param $cprefs Array: client's acceptable type list
 * @param $sprefs Array: server's offered types
 * @return string
 *
 * @todo FIXME: Doesn't handle params like 'text/plain; charset=UTF-8'
 * XXX: generalize to negotiate other stuff
 */
function wfNegotiateType( $cprefs, $sprefs ) {
	$combine = array();

	foreach( array_keys( $sprefs ) as $type ) {
		$parts = explode( '/', $type );
		if( $parts[1] != '*' ) {
			$ckey = mimeTypeMatch( $type, $cprefs );
			if( $ckey ) {
				$combine[$type] = $sprefs[$type] * $cprefs[$ckey];
			}
		}
	}

	foreach( array_keys( $cprefs ) as $type ) {
		$parts = explode( '/', $type );
		if( $parts[1] != '*' && !array_key_exists( $type, $sprefs ) ) {
			$skey = mimeTypeMatch( $type, $sprefs );
			if( $skey ) {
				$combine[$type] = $sprefs[$skey] * $cprefs[$type];
			}
		}
	}

	$bestq = 0;
	$besttype = null;

	foreach( array_keys( $combine ) as $type ) {
		if( $combine[$type] > $bestq ) {
			$besttype = $type;
			$bestq = $combine[$type];
		}
	}

	return $besttype;
}

/**
 * Reference-counted warning suppression
 *
 * @param $end Bool
 */
function wfSuppressWarnings( $end = false ) {
	static $suppressCount = 0;
	static $originalLevel = false;

	if ( $end ) {
		if ( $suppressCount ) {
			--$suppressCount;
			if ( !$suppressCount ) {
				error_reporting( $originalLevel );
			}
		}
	} else {
		if ( !$suppressCount ) {
			// E_DEPRECATED and E_USER_DEPRECATED are undefined in PHP 5.2
			if( !defined( 'E_DEPRECATED' ) ) {
				define( 'E_DEPRECATED', 8192 );
			}
			if( !defined( 'E_USER_DEPRECATED' ) ) {
				define( 'E_USER_DEPRECATED', 16384 );
			}
			$originalLevel = error_reporting( E_ALL & ~( E_WARNING | E_NOTICE | E_USER_WARNING | E_USER_NOTICE | E_DEPRECATED | E_USER_DEPRECATED | E_STRICT ) );
		}
		++$suppressCount;
	}
}

/**
 * Restore error level to previous value
 */
function wfRestoreWarnings() {
	wfSuppressWarnings( true );
}

# Autodetect, convert and provide timestamps of various types

/**
 * Unix time - the number of seconds since 1970-01-01 00:00:00 UTC
 */
define( 'TS_UNIX', 0 );

/**
 * MediaWiki concatenated string timestamp (YYYYMMDDHHMMSS)
 */
define( 'TS_MW', 1 );

/**
 * MySQL DATETIME (YYYY-MM-DD HH:MM:SS)
 */
define( 'TS_DB', 2 );

/**
 * RFC 2822 format, for E-mail and HTTP headers
 */
define( 'TS_RFC2822', 3 );

/**
 * ISO 8601 format with no timezone: 1986-02-09T20:00:00Z
 *
 * This is used by Special:Export
 */
define( 'TS_ISO_8601', 4 );

/**
 * An Exif timestamp (YYYY:MM:DD HH:MM:SS)
 *
 * @see http://exif.org/Exif2-2.PDF The Exif 2.2 spec, see page 28 for the
 *       DateTime tag and page 36 for the DateTimeOriginal and
 *       DateTimeDigitized tags.
 */
define( 'TS_EXIF', 5 );

/**
 * Oracle format time.
 */
define( 'TS_ORACLE', 6 );

/**
 * Postgres format time.
 */
define( 'TS_POSTGRES', 7 );

/**
 * DB2 format time
 */
define( 'TS_DB2', 8 );

/**
 * ISO 8601 basic format with no timezone: 19860209T200000Z.  This is used by ResourceLoader
 */
define( 'TS_ISO_8601_BASIC', 9 );

/**
 * Get a timestamp string in one of various formats
 *
 * @param $outputtype Mixed: A timestamp in one of the supported formats, the
 *                    function will autodetect which format is supplied and act
 *                    accordingly.
 * @param $ts Mixed: the timestamp to convert or 0 for the current timestamp
 * @return Mixed: String / false The same date in the format specified in $outputtype or false
 */
function wfTimestamp( $outputtype = TS_UNIX, $ts = 0 ) {
	$uts = 0;
	$da = array();
	$strtime = '';

	if ( !$ts ) { // We want to catch 0, '', null... but not date strings starting with a letter.
		$uts = time();
		$strtime = "@$uts";
	} elseif ( preg_match( '/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d)$/D', $ts, $da ) ) {
		# TS_DB
	} elseif ( preg_match( '/^(\d{4}):(\d\d):(\d\d) (\d\d):(\d\d):(\d\d)$/D', $ts, $da ) ) {
		# TS_EXIF
	} elseif ( preg_match( '/^(\d{4})(\d\d)(\d\d)(\d\d)(\d\d)(\d\d)$/D', $ts, $da ) ) {
		# TS_MW
	} elseif ( preg_match( '/^-?\d{1,13}$/D', $ts ) ) {
		# TS_UNIX
		$uts = $ts;
		$strtime = "@$ts"; // http://php.net/manual/en/datetime.formats.compound.php
	} elseif ( preg_match( '/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}.\d{6}$/', $ts ) ) {
		# TS_ORACLE // session altered to DD-MM-YYYY HH24:MI:SS.FF6
		$strtime = preg_replace( '/(\d\d)\.(\d\d)\.(\d\d)(\.(\d+))?/', "$1:$2:$3",
				str_replace( '+00:00', 'UTC', $ts ) );
	} elseif ( preg_match( '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(?:\.*\d*)?Z$/', $ts, $da ) ) {
		# TS_ISO_8601
	} elseif ( preg_match( '/^(\d{4})(\d{2})(\d{2})T(\d{2})(\d{2})(\d{2})(?:\.*\d*)?Z$/', $ts, $da ) ) {
		#TS_ISO_8601_BASIC
	} elseif ( preg_match( '/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d)\.*\d*[\+\- ](\d\d)$/', $ts, $da ) ) {
		# TS_POSTGRES
	} elseif ( preg_match( '/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d)\.*\d* GMT$/', $ts, $da ) ) {
		# TS_POSTGRES
	} elseif (preg_match( '/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d)\.\d\d\d$/', $ts, $da ) ) {
		# TS_DB2
	} elseif ( preg_match( '/^[ \t\r\n]*([A-Z][a-z]{2},[ \t\r\n]*)?' . # Day of week
							'\d\d?[ \t\r\n]*[A-Z][a-z]{2}[ \t\r\n]*\d{2}(?:\d{2})?' .  # dd Mon yyyy
							'[ \t\r\n]*\d\d[ \t\r\n]*:[ \t\r\n]*\d\d[ \t\r\n]*:[ \t\r\n]*\d\d/S', $ts ) ) { # hh:mm:ss
		# TS_RFC2822, accepting a trailing comment. See http://www.squid-cache.org/mail-archive/squid-users/200307/0122.html / r77171
		# The regex is a superset of rfc2822 for readability
		$strtime = strtok( $ts, ';' );
	} elseif ( preg_match( '/^[A-Z][a-z]{5,8}, \d\d-[A-Z][a-z]{2}-\d{2} \d\d:\d\d:\d\d/', $ts ) ) {
		# TS_RFC850
		$strtime = $ts;
	} elseif ( preg_match( '/^[A-Z][a-z]{2} [A-Z][a-z]{2} +\d{1,2} \d\d:\d\d:\d\d \d{4}/', $ts ) ) {
		# asctime
		$strtime = $ts;
	} else {
		# Bogus value...
		wfDebug("wfTimestamp() fed bogus time value: TYPE=$outputtype; VALUE=$ts\n");

		return false;
	}

	static $formats = array(
		TS_UNIX => 'U',
		TS_MW => 'YmdHis',
		TS_DB => 'Y-m-d H:i:s',
		TS_ISO_8601 => 'Y-m-d\TH:i:s\Z',
		TS_ISO_8601_BASIC => 'Ymd\THis\Z',
		TS_EXIF => 'Y:m:d H:i:s', // This shouldn't ever be used, but is included for completeness
		TS_RFC2822 => 'D, d M Y H:i:s',
		TS_ORACLE => 'd-m-Y H:i:s.000000', // Was 'd-M-y h.i.s A' . ' +00:00' before r51500
		TS_POSTGRES => 'Y-m-d H:i:s',
		TS_DB2 => 'Y-m-d H:i:s',
	);

	if ( !isset( $formats[$outputtype] ) ) {
		throw new MWException( 'wfTimestamp() called with illegal output type.' );
	}

	if ( function_exists( "date_create" ) ) {
		if ( count( $da ) ) {
			$ds = sprintf("%04d-%02d-%02dT%02d:%02d:%02d.00+00:00",
				(int)$da[1], (int)$da[2], (int)$da[3],
				(int)$da[4], (int)$da[5], (int)$da[6]);

			$d = date_create( $ds, new DateTimeZone( 'GMT' ) );
		} elseif ( $strtime ) {
			$d = date_create( $strtime, new DateTimeZone( 'GMT' ) );
		} else {
			return false;
		}

		if ( !$d ) {
			wfDebug("wfTimestamp() fed bogus time value: $outputtype; $ts\n");
			return false;
		}

		$output = $d->format( $formats[$outputtype] );
	} else {
		if ( count( $da ) ) {
			// Warning! gmmktime() acts oddly if the month or day is set to 0
			// We may want to handle that explicitly at some point
			$uts = gmmktime( (int)$da[4], (int)$da[5], (int)$da[6],
				(int)$da[2], (int)$da[3], (int)$da[1] );
		} elseif ( $strtime ) {
			$uts = strtotime( $strtime );
		}

		if ( $uts === false ) {
			wfDebug("wfTimestamp() can't parse the timestamp (non 32-bit time? Update php): $outputtype; $ts\n");
			return false;
		}

		if ( TS_UNIX == $outputtype ) {
			return $uts;
		}
		$output = gmdate( $formats[$outputtype], $uts );
	}

	if ( ( $outputtype == TS_RFC2822 ) || ( $outputtype == TS_POSTGRES ) ) {
		$output .= ' GMT';
	}

	return $output;
}

/**
 * Return a formatted timestamp, or null if input is null.
 * For dealing with nullable timestamp columns in the database.
 *
 * @param $outputtype Integer
 * @param $ts String
 * @return String
 */
function wfTimestampOrNull( $outputtype = TS_UNIX, $ts = null ) {
	if( is_null( $ts ) ) {
		return null;
	} else {
		return wfTimestamp( $outputtype, $ts );
	}
}

/**
 * Convenience function; returns MediaWiki timestamp for the present time.
 *
 * @return string
 */
function wfTimestampNow() {
	# return NOW
	return wfTimestamp( TS_MW, time() );
}

/**
 * Check if the operating system is Windows
 *
 * @return Bool: true if it's Windows, False otherwise.
 */
function wfIsWindows() {
	static $isWindows = null;
	if ( $isWindows === null ) {
		$isWindows = substr( php_uname(), 0, 7 ) == 'Windows';
	}
	return $isWindows;
}

/**
 * Check if we are running under HipHop
 *
 * @return Bool
 */
function wfIsHipHop() {
	return function_exists( 'hphp_thread_set_warmup_enabled' );
}

/**
 * Swap two variables
 *
 * @param $x Mixed
 * @param $y Mixed
 */
function swap( &$x, &$y ) {
	$z = $x;
	$x = $y;
	$y = $z;
}

/**
 * Tries to get the system directory for temporary files. The TMPDIR, TMP, and
 * TEMP environment variables are then checked in sequence, and if none are set
 * try sys_get_temp_dir() for PHP >= 5.2.1. All else fails, return /tmp for Unix
 * or C:\Windows\Temp for Windows and hope for the best.
 * It is common to call it with tempnam().
 *
 * NOTE: When possible, use instead the tmpfile() function to create
 * temporary files to avoid race conditions on file creation, etc.
 *
 * @return String
 */
function wfTempDir() {
	foreach( array( 'TMPDIR', 'TMP', 'TEMP' ) as $var ) {
		$tmp = getenv( $var );
		if( $tmp && file_exists( $tmp ) && is_dir( $tmp ) && is_writable( $tmp ) ) {
			return $tmp;
		}
	}
	if( function_exists( 'sys_get_temp_dir' ) ) {
		return sys_get_temp_dir();
	}
	# Usual defaults
	return wfIsWindows() ? 'C:\Windows\Temp' : '/tmp';
}

/**
 * Make directory, and make all parent directories if they don't exist
 *
 * @param $dir String: full path to directory to create
 * @param $mode Integer: chmod value to use, default is $wgDirectoryMode
 * @param $caller String: optional caller param for debugging.
 * @return bool
 */
function wfMkdirParents( $dir, $mode = null, $caller = null ) {
	global $wgDirectoryMode;

	if ( FileBackend::isStoragePath( $dir ) ) { // sanity
		throw new MWException( __FUNCTION__ . " given storage path `$dir`.");
	}

	if ( !is_null( $caller ) ) {
		wfDebug( "$caller: called wfMkdirParents($dir)\n" );
	}

	if( strval( $dir ) === '' || file_exists( $dir ) ) {
		return true;
	}

	$dir = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, $dir );

	if ( is_null( $mode ) ) {
		$mode = $wgDirectoryMode;
	}

	// Turn off the normal warning, we're doing our own below
	wfSuppressWarnings();
	$ok = mkdir( $dir, $mode, true ); // PHP5 <3
	wfRestoreWarnings();

	if( !$ok ) {
		// PHP doesn't report the path in its warning message, so add our own to aid in diagnosis.
		trigger_error( __FUNCTION__ . ": failed to mkdir \"$dir\" mode $mode", E_USER_WARNING );
	}
	return $ok;
}

/**
 * Increment a statistics counter
 *
 * @param $key String
 * @param $count Int
 */
function wfIncrStats( $key, $count = 1 ) {
	global $wgStatsMethod;

	$count = intval( $count );

	if( $wgStatsMethod == 'udp' ) {
		global $wgUDPProfilerHost, $wgUDPProfilerPort, $wgDBname, $wgAggregateStatsID;
		static $socket;

		$id = $wgAggregateStatsID !== false ? $wgAggregateStatsID : $wgDBname;

		if ( !$socket ) {
			$socket = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
			$statline = "stats/{$id} - {$count} 1 1 1 1 -total\n";
			socket_sendto(
				$socket,
				$statline,
				strlen( $statline ),
				0,
				$wgUDPProfilerHost,
				$wgUDPProfilerPort
			);
		}
		$statline = "stats/{$id} - {$count} 1 1 1 1 {$key}\n";
		wfSuppressWarnings();
		socket_sendto(
			$socket,
			$statline,
			strlen( $statline ),
			0,
			$wgUDPProfilerHost,
			$wgUDPProfilerPort
		);
		wfRestoreWarnings();
	} elseif( $wgStatsMethod == 'cache' ) {
		global $wgMemc;
		$key = wfMemcKey( 'stats', $key );
		if ( is_null( $wgMemc->incr( $key, $count ) ) ) {
			$wgMemc->add( $key, $count );
		}
	} else {
		// Disabled
	}
}

/**
 * Remove a directory and all its content.
 * Does not hide error.
 */
function wfRecursiveRemoveDir( $dir ) {
	wfDebug( __FUNCTION__ . "( $dir )\n" );
	// taken from http://de3.php.net/manual/en/function.rmdir.php#98622
	if ( is_dir( $dir ) ) {
		$objects = scandir( $dir );
		foreach ( $objects as $object ) {
			if ( $object != "." && $object != ".." ) {
				if ( filetype( $dir . '/' . $object ) == "dir" ) {
					wfRecursiveRemoveDir( $dir . '/' . $object );
				} else {
					unlink( $dir . '/' . $object );
				}
			}
		}
		reset( $objects );
		rmdir( $dir );
	}
}

/**
 * @param $nr Mixed: the number to format
 * @param $acc Integer: the number of digits after the decimal point, default 2
 * @param $round Boolean: whether or not to round the value, default true
 * @return float
 */
function wfPercent( $nr, $acc = 2, $round = true ) {
	$ret = sprintf( "%.${acc}f", $nr );
	return $round ? round( $ret, $acc ) . '%' : "$ret%";
}

/**
 * Find out whether or not a mixed variable exists in a string
 *
 * @param $needle String
 * @param $str String
 * @param $insensitive Boolean
 * @return Boolean
 */
function in_string( $needle, $str, $insensitive = false ) {
	$func = 'strpos';
	if( $insensitive ) $func = 'stripos';

	return $func( $str, $needle ) !== false;
}

/**
 * Safety wrapper around ini_get() for boolean settings.
 * The values returned from ini_get() are pre-normalized for settings
 * set via php.ini or php_flag/php_admin_flag... but *not*
 * for those set via php_value/php_admin_value.
 *
 * It's fairly common for people to use php_value instead of php_flag,
 * which can leave you with an 'off' setting giving a false positive
 * for code that just takes the ini_get() return value as a boolean.
 *
 * To make things extra interesting, setting via php_value accepts
 * "true" and "yes" as true, but php.ini and php_flag consider them false. :)
 * Unrecognized values go false... again opposite PHP's own coercion
 * from string to bool.
 *
 * Luckily, 'properly' set settings will always come back as '0' or '1',
 * so we only have to worry about them and the 'improper' settings.
 *
 * I frickin' hate PHP... :P
 *
 * @param $setting String
 * @return Bool
 */
function wfIniGetBool( $setting ) {
	$val = ini_get( $setting );
	// 'on' and 'true' can't have whitespace around them, but '1' can.
	return strtolower( $val ) == 'on'
		|| strtolower( $val ) == 'true'
		|| strtolower( $val ) == 'yes'
		|| preg_match( "/^\s*[+-]?0*[1-9]/", $val ); // approx C atoi() function
}

/**
 * Wrapper function for PHP's dl(). This doesn't work in most situations from
 * PHP 5.3 onward, and is usually disabled in shared environments anyway.
 *
 * @param $extension String A PHP extension. The file suffix (.so or .dll)
 *                          should be omitted
 * @param $fileName String Name of the library, if not $extension.suffix
 * @return Bool - Whether or not the extension is loaded
 */
function wfDl( $extension, $fileName = null ) {
	if( extension_loaded( $extension ) ) {
		return true;
	}

	$canDl = false;
	$sapi = php_sapi_name();
	if( version_compare( PHP_VERSION, '5.3.0', '<' ) ||
		$sapi == 'cli' || $sapi == 'cgi' || $sapi == 'embed' )
	{
		$canDl = ( function_exists( 'dl' ) && is_callable( 'dl' )
		&& wfIniGetBool( 'enable_dl' ) && !wfIniGetBool( 'safe_mode' ) );
	}

	if( $canDl ) {
		$fileName = $fileName ? $fileName : $extension;
		if( wfIsWindows() ) {
			$fileName = 'php_' . $fileName;
		}
		wfSuppressWarnings();
		dl( $fileName . '.' . PHP_SHLIB_SUFFIX );
		wfRestoreWarnings();
	}
	return extension_loaded( $extension );
}

/**
 * Windows-compatible version of escapeshellarg()
 * Windows doesn't recognise single-quotes in the shell, but the escapeshellarg()
 * function puts single quotes in regardless of OS.
 *
 * Also fixes the locale problems on Linux in PHP 5.2.6+ (bug backported to
 * earlier distro releases of PHP)
 *
 * @param varargs
 * @return String
 */
function wfEscapeShellArg( ) {
	wfInitShellLocale();

	$args = func_get_args();
	$first = true;
	$retVal = '';
	foreach ( $args as $arg ) {
		if ( !$first ) {
			$retVal .= ' ';
		} else {
			$first = false;
		}

		if ( wfIsWindows() ) {
			// Escaping for an MSVC-style command line parser and CMD.EXE
			// Refs:
			//  * http://web.archive.org/web/20020708081031/http://mailman.lyra.org/pipermail/scite-interest/2002-March/000436.html
			//  * http://technet.microsoft.com/en-us/library/cc723564.aspx
			//  * Bug #13518
			//  * CR r63214
			// Double the backslashes before any double quotes. Escape the double quotes.
			$tokens = preg_split( '/(\\\\*")/', $arg, -1, PREG_SPLIT_DELIM_CAPTURE );
			$arg = '';
			$iteration = 0;
			foreach ( $tokens as $token ) {
				if ( $iteration % 2 == 1 ) {
					// Delimiter, a double quote preceded by zero or more slashes
					$arg .= str_replace( '\\', '\\\\', substr( $token, 0, -1 ) ) . '\\"';
				} elseif ( $iteration % 4 == 2 ) {
					// ^ in $token will be outside quotes, need to be escaped
					$arg .= str_replace( '^', '^^', $token );
				} else { // $iteration % 4 == 0
					// ^ in $token will appear inside double quotes, so leave as is
					$arg .= $token;
				}
				$iteration++;
			}
			// Double the backslashes before the end of the string, because
			// we will soon add a quote
			$m = array();
			if ( preg_match( '/^(.*?)(\\\\+)$/', $arg, $m ) ) {
				$arg = $m[1] . str_replace( '\\', '\\\\', $m[2] );
			}

			// Add surrounding quotes
			$retVal .= '"' . $arg . '"';
		} else {
			$retVal .= escapeshellarg( $arg );
		}
	}
	return $retVal;
}

/**
 * Execute a shell command, with time and memory limits mirrored from the PHP
 * configuration if supported.
 * @param $cmd String Command line, properly escaped for shell.
 * @param &$retval int optional, will receive the program's exit code.
 *                 (non-zero is usually failure)
 * @param $environ Array optional environment variables which should be
 *                 added to the executed command environment.
 * @return string collected stdout as a string (trailing newlines stripped)
 */
function wfShellExec( $cmd, &$retval = null, $environ = array() ) {
	global $IP, $wgMaxShellMemory, $wgMaxShellFileSize, $wgMaxShellTime;

	static $disabled;
	if ( is_null( $disabled ) ) {
		$disabled = false;
		if( wfIniGetBool( 'safe_mode' ) ) {
			wfDebug( "wfShellExec can't run in safe_mode, PHP's exec functions are too broken.\n" );
			$disabled = 'safemode';
		} else {
			$functions = explode( ',', ini_get( 'disable_functions' ) );
			$functions = array_map( 'trim', $functions );
			$functions = array_map( 'strtolower', $functions );
			if ( in_array( 'passthru', $functions ) ) {
				wfDebug( "passthru is in disabled_functions\n" );
				$disabled = 'passthru';
			}
		}
	}
	if ( $disabled ) {
		$retval = 1;
		return $disabled == 'safemode' ?
			'Unable to run external programs in safe mode.' :
			'Unable to run external programs, passthru() is disabled.';
	}

	wfInitShellLocale();

	$envcmd = '';
	foreach( $environ as $k => $v ) {
		if ( wfIsWindows() ) {
			/* Surrounding a set in quotes (method used by wfEscapeShellArg) makes the quotes themselves
			 * appear in the environment variable, so we must use carat escaping as documented in
			 * http://technet.microsoft.com/en-us/library/cc723564.aspx
			 * Note however that the quote isn't listed there, but is needed, and the parentheses
			 * are listed there but doesn't appear to need it.
			 */
			$envcmd .= "set $k=" . preg_replace( '/([&|()<>^"])/', '^\\1', $v ) . '&& ';
		} else {
			/* Assume this is a POSIX shell, thus required to accept variable assignments before the command
			 * http://www.opengroup.org/onlinepubs/009695399/utilities/xcu_chap02.html#tag_02_09_01
			 */
			$envcmd .= "$k=" . escapeshellarg( $v ) . ' ';
		}
	}
	$cmd = $envcmd . $cmd;

	if ( wfIsWindows() ) {
		if ( version_compare( PHP_VERSION, '5.3.0', '<' ) && /* Fixed in 5.3.0 :) */
			( version_compare( PHP_VERSION, '5.2.1', '>=' ) || php_uname( 's' ) == 'Windows NT' ) )
		{
			# Hack to work around PHP's flawed invocation of cmd.exe
			# http://news.php.net/php.internals/21796
			# Windows 9x doesn't accept any kind of quotes
			$cmd = '"' . $cmd . '"';
		}
	} elseif ( php_uname( 's' ) == 'Linux' ) {
		$time = intval( $wgMaxShellTime );
		$mem = intval( $wgMaxShellMemory );
		$filesize = intval( $wgMaxShellFileSize );

		if ( $time > 0 && $mem > 0 ) {
			$script = "$IP/bin/ulimit4.sh";
			if ( is_executable( $script ) ) {
				$cmd = '/bin/bash ' . escapeshellarg( $script ) . " $time $mem $filesize " . escapeshellarg( $cmd );
			}
		}
	}
	wfDebug( "wfShellExec: $cmd\n" );

	$retval = 1; // error by default?
	ob_start();
	passthru( $cmd, $retval );
	$output = ob_get_contents();
	ob_end_clean();

	// Wikia change - begin
	if ( $retval > 0 ) {
		Wikia\Logger\WikiaLogger::instance()->error( 'wfShellExec failed', [
			'exception' => new Exception( $cmd, $retval ),
			'output' => $output,
			'load_avg' => implode( ', ', sys_getloadavg() ),
		]);
	}
	// Wikia change - end

	if ( $retval == 127 ) {
		wfDebugLog( 'exec', "Possibly missing executable file: $cmd\n" );
	}

	return $output;
}

/**
 * Workaround for http://bugs.php.net/bug.php?id=45132
 * escapeshellarg() destroys non-ASCII characters if LANG is not a UTF-8 locale
 */
function wfInitShellLocale() {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done = true;
	global $wgShellLocale;
	if ( !wfIniGetBool( 'safe_mode' ) ) {
		putenv( "LC_CTYPE=$wgShellLocale" );
		setlocale( LC_CTYPE, $wgShellLocale );
	}
}

/**
 * Generate a shell-escaped command line string to run a maintenance script.
 * Note that $parameters should be a flat array and an option with an argument
 * should consist of two consecutive items in the array (do not use "--option value").
 * @param $script string MediaWiki maintenance script path
 * @param $parameters Array Arguments and options to the script
 * @param $options Array Associative array of options:
 * 		'php': The path to the php executable
 * 		'wrapper': Path to a PHP wrapper to handle the maintenance script
 * @return Array
 */
function wfShellMaintenanceCmd( $script, array $parameters = array(), array $options = array() ) {
	global $wgPhpCli;
	// Give site config file a chance to run the script in a wrapper.
	// The caller may likely want to call wfBasename() on $script.
	wfRunHooks( 'wfShellMaintenanceCmd', array( &$script, &$parameters, &$options ) );
	$cmd = isset( $options['php'] ) ? array( $options['php'] ) : array( $wgPhpCli );
	if ( isset( $options['wrapper'] ) ) {
		$cmd[] = $options['wrapper'];
	}
	$cmd[] = $script;
	// Escape each parameter for shell
	return implode( " ", array_map( 'wfEscapeShellArg', array_merge( $cmd, $parameters ) ) );
}

/**
 * wfMerge attempts to merge differences between three texts.
 * Returns true for a clean merge and false for failure or a conflict.
 *
 * @param $old String
 * @param $mine String
 * @param $yours String
 * @param $result String
 * @return Bool
 */
function wfMerge( $old, $mine, $yours, &$result ) {
	global $wgDiff3;

	# This check may also protect against code injection in
	# case of broken installations.
	wfSuppressWarnings();
	$haveDiff3 = $wgDiff3 && file_exists( $wgDiff3 );
	wfRestoreWarnings();

	if( !$haveDiff3 ) {
		wfDebug( "diff3 not found\n" );
		return false;
	}

	# Make temporary files
	$td = wfTempDir();
	$oldtextFile = fopen( $oldtextName = tempnam( $td, 'merge-old-' ), 'w' );
	$mytextFile = fopen( $mytextName = tempnam( $td, 'merge-mine-' ), 'w' );
	$yourtextFile = fopen( $yourtextName = tempnam( $td, 'merge-your-' ), 'w' );

	fwrite( $oldtextFile, $old );
	fclose( $oldtextFile );
	fwrite( $mytextFile, $mine );
	fclose( $mytextFile );
	fwrite( $yourtextFile, $yours );
	fclose( $yourtextFile );

	# Check for a conflict
	$cmd = $wgDiff3 . ' -a --overlap-only ' .
		wfEscapeShellArg( $mytextName ) . ' ' .
		wfEscapeShellArg( $oldtextName ) . ' ' .
		wfEscapeShellArg( $yourtextName );
	$handle = popen( $cmd, 'r' );

	if( fgets( $handle, 1024 ) ) {
		$conflict = true;
	} else {
		$conflict = false;
	}
	pclose( $handle );

	# Merge differences
	$cmd = $wgDiff3 . ' -a -e --merge ' .
		wfEscapeShellArg( $mytextName, $oldtextName, $yourtextName );
	$handle = popen( $cmd, 'r' );
	$result = '';
	do {
		$data = fread( $handle, 8192 );
		if ( strlen( $data ) == 0 ) {
			break;
		}
		$result .= $data;
	} while ( true );
	pclose( $handle );
	unlink( $mytextName );
	unlink( $oldtextName );
	unlink( $yourtextName );

	if ( $result === '' && $old !== '' && !$conflict ) {
		wfDebug( "Unexpected null result from diff3. Command: $cmd\n" );
		$conflict = true;
	}
	return !$conflict;
}

/**
 * Returns unified plain-text diff of two texts.
 * Useful for machine processing of diffs.
 *
 * @param $before String: the text before the changes.
 * @param $after String: the text after the changes.
 * @param $params String: command-line options for the diff command.
 * @return String: unified diff of $before and $after
 */
function wfDiff( $before, $after, $params = '-u' ) {
	if ( $before == $after ) {
		return '';
	}

	global $wgDiff;
	wfSuppressWarnings();
	$haveDiff = $wgDiff && file_exists( $wgDiff );
	wfRestoreWarnings();

	# This check may also protect against code injection in
	# case of broken installations.
	if( !$haveDiff ) {
		wfDebug( "diff executable not found\n" );
		$diffs = new Diff( explode( "\n", $before ), explode( "\n", $after ) );
		$format = new UnifiedDiffFormatter();
		return $format->format( $diffs );
	}

	# Make temporary files
	$td = wfTempDir();
	$oldtextFile = fopen( $oldtextName = tempnam( $td, 'merge-old-' ), 'w' );
	$newtextFile = fopen( $newtextName = tempnam( $td, 'merge-your-' ), 'w' );

	fwrite( $oldtextFile, $before );
	fclose( $oldtextFile );
	fwrite( $newtextFile, $after );
	fclose( $newtextFile );

	// Get the diff of the two files
	$cmd = "$wgDiff " . $params . ' ' . wfEscapeShellArg( $oldtextName, $newtextName );

	$h = popen( $cmd, 'r' );

	$diff = '';

	do {
		$data = fread( $h, 8192 );
		if ( strlen( $data ) == 0 ) {
			break;
		}
		$diff .= $data;
	} while ( true );

	// Clean up
	pclose( $h );
	unlink( $oldtextName );
	unlink( $newtextName );

	// Kill the --- and +++ lines. They're not useful.
	$diff_lines = explode( "\n", $diff );
	if ( strpos( $diff_lines[0], '---' ) === 0 ) {
		unset( $diff_lines[0] );
	}
	if ( strpos( $diff_lines[1], '+++' ) === 0 ) {
		unset( $diff_lines[1] );
	}

	$diff = implode( "\n", $diff_lines );

	return $diff;
}

/**
 * This function works like "use VERSION" in Perl, the program will die with a
 * backtrace if the current version of PHP is less than the version provided
 *
 * This is useful for extensions which due to their nature are not kept in sync
 * with releases, and might depend on other versions of PHP than the main code
 *
 * Note: PHP might die due to parsing errors in some cases before it ever
 *       manages to call this function, such is life
 *
 * @see perldoc -f use
 *
 * @param $req_ver Mixed: the version to check, can be a string, an integer, or
 *                 a float
 */
function wfUsePHP( $req_ver ) {
	$php_ver = PHP_VERSION;

	if ( version_compare( $php_ver, (string)$req_ver, '<' ) ) {
		throw new MWException( "PHP $req_ver required--this is only $php_ver" );
	}
}

/**
 * This function works like "use VERSION" in Perl except it checks the version
 * of MediaWiki, the program will die with a backtrace if the current version
 * of MediaWiki is less than the version provided.
 *
 * This is useful for extensions which due to their nature are not kept in sync
 * with releases
 *
 * @see perldoc -f use
 *
 * @param $req_ver Mixed: the version to check, can be a string, an integer, or
 *                 a float
 */
function wfUseMW( $req_ver ) {
	global $wgVersion;

	if ( version_compare( $wgVersion, (string)$req_ver, '<' ) ) {
		throw new MWException( "MediaWiki $req_ver required--this is only $wgVersion" );
	}
}

/**
 * Return the final portion of a pathname.
 * Reimplemented because PHP5's basename() is buggy with multibyte text.
 * http://bugs.php.net/bug.php?id=33898
 *
 * PHP's basename() only considers '\' a pathchar on Windows and Netware.
 * We'll consider it so always, as we don't want \s in our Unix paths either.
 *
 * @param $path String
 * @param $suffix String: to remove if present
 * @return String
 */
function wfBaseName( $path, $suffix = '' ) {
	$encSuffix = ( $suffix == '' )
		? ''
		: ( '(?:' . preg_quote( $suffix, '#' ) . ')?' );
	$matches = array();
	if( preg_match( "#([^/\\\\]*?){$encSuffix}[/\\\\]*$#", $path, $matches ) ) {
		return $matches[1];
	} else {
		return '';
	}
}

/**
 * Generate a relative path name to the given file.
 * May explode on non-matching case-insensitive paths,
 * funky symlinks, etc.
 *
 * @param $path String: absolute destination path including target filename
 * @param $from String: Absolute source path, directory only
 * @return String
 */
function wfRelativePath( $path, $from ) {
	// Normalize mixed input on Windows...
	$path = str_replace( '/', DIRECTORY_SEPARATOR, $path );
	$from = str_replace( '/', DIRECTORY_SEPARATOR, $from );

	// Trim trailing slashes -- fix for drive root
	$path = rtrim( $path, DIRECTORY_SEPARATOR );
	$from = rtrim( $from, DIRECTORY_SEPARATOR );

	$pieces = explode( DIRECTORY_SEPARATOR, dirname( $path ) );
	$against = explode( DIRECTORY_SEPARATOR, $from );

	if( $pieces[0] !== $against[0] ) {
		// Non-matching Windows drive letters?
		// Return a full path.
		return $path;
	}

	// Trim off common prefix
	while( count( $pieces ) && count( $against )
		&& $pieces[0] == $against[0] ) {
		array_shift( $pieces );
		array_shift( $against );
	}

	// relative dots to bump us to the parent
	while( count( $against ) ) {
		array_unshift( $pieces, '..' );
		array_shift( $against );
	}

	array_push( $pieces, wfBaseName( $path ) );

	return implode( DIRECTORY_SEPARATOR, $pieces );
}

/**
 * Do any deferred updates and clear the list
 *
 * @deprecated since 1.19
 * @see DeferredUpdates::doUpdate()
 * @param $commit string
 */
function wfDoUpdates( $commit = '' ) {
	wfDeprecated( __METHOD__, '1.19' );
	DeferredUpdates::doUpdates( $commit );
}

/**
 * Convert an arbitrarily-long digit string from one numeric base
 * to another, optionally zero-padding to a minimum column width.
 *
 * Supports base 2 through 36; digit values 10-36 are represented
 * as lowercase letters a-z. Input is case-insensitive.
 *
 * @param $input String: of digits
 * @param $sourceBase Integer: 2-36
 * @param $destBase Integer: 2-36
 * @param $pad Integer: 1 or greater
 * @param $lowercase Boolean
 * @return String or false on invalid input
 */
function wfBaseConvert( $input, $sourceBase, $destBase, $pad = 1, $lowercase = true ) {
	$input = strval( $input );
	if( $sourceBase < 2 ||
		$sourceBase > 36 ||
		$destBase < 2 ||
		$destBase > 36 ||
		$pad < 1 ||
		$sourceBase != intval( $sourceBase ) ||
		$destBase != intval( $destBase ) ||
		$pad != intval( $pad ) ||
		!is_string( $input ) ||
		$input == '' ) {
		return false;
	}
	$digitChars = ( $lowercase ) ? '0123456789abcdefghijklmnopqrstuvwxyz' : '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$inDigits = array();
	$outChars = '';

	// Decode and validate input string
	$input = strtolower( $input );
	for( $i = 0; $i < strlen( $input ); $i++ ) {
		$n = strpos( $digitChars, $input[$i] );
		if( $n === false || $n > $sourceBase ) {
			return false;
		}
		$inDigits[] = $n;
	}

	// Iterate over the input, modulo-ing out an output digit
	// at a time until input is gone.
	while( count( $inDigits ) ) {
		$work = 0;
		$workDigits = array();

		// Long division...
		foreach( $inDigits as $digit ) {
			$work *= $sourceBase;
			$work += $digit;

			if( $work < $destBase ) {
				// Gonna need to pull another digit.
				if( count( $workDigits ) ) {
					// Avoid zero-padding; this lets us find
					// the end of the input very easily when
					// length drops to zero.
					$workDigits[] = 0;
				}
			} else {
				// Finally! Actual division!
				$workDigits[] = intval( $work / $destBase );

				// Isn't it annoying that most programming languages
				// don't have a single divide-and-remainder operator,
				// even though the CPU implements it that way?
				$work = $work % $destBase;
			}
		}

		// All that division leaves us with a remainder,
		// which is conveniently our next output digit.
		$outChars .= $digitChars[$work];

		// And we continue!
		$inDigits = $workDigits;
	}

	while( strlen( $outChars ) < $pad ) {
		$outChars .= '0';
	}

	return strrev( $outChars );
}

/**
 * Create an object with a given name and an array of construct parameters
 *
 * @param $name String
 * @param $p Array: parameters
 * @return object
 * @deprecated since 1.18, warnings in 1.18, removal in 1.20
 */
function wfCreateObject( $name, $p ) {
	wfDeprecated( __FUNCTION__, '1.18' );
	return MWFunction::newObj( $name, $p );
}

/**
 * @return bool
 */
function wfHttpOnlySafe() {
	global $wgHttpOnlyBlacklist;

	if( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
		foreach( $wgHttpOnlyBlacklist as $regex ) {
			if( preg_match( $regex, $_SERVER['HTTP_USER_AGENT'] ) ) {
				return false;
			}
		}
	}

	return true;
}

/**
 * Check if there is sufficent entropy in php's built-in session generation
 * PHP's built-in session entropy is enabled if:
 * - entropy_file is set or you're on Windows with php 5.3.3+
 * - AND entropy_length is > 0
 * We treat it as disabled if it doesn't have an entropy length of at least 32
 *
 * @return bool true = there is sufficient entropy
 */
function wfCheckEntropy() {
	return (
			( wfIsWindows() && version_compare( PHP_VERSION, '5.3.3', '>=' ) )
			|| ini_get( 'session.entropy_file' )
		)
		&& intval( ini_get( 'session.entropy_length' ) ) >= 32;
}

/**
 * Override session_id before session startup if php's built-in
 * session generation code is not secure.
 */
function wfFixSessionID() {
	// If the cookie or session id is already set we already have a session and should abort
	if ( !empty( $_COOKIE[ session_name() ] ) || session_id() ) {
		return;
	}
	session_id( MWCryptRand::generateHex( 32 ) );
}

/**
 * Reset the session_id
 *
 * Backported from MW 1.22
 */
function wfResetSessionID() {
	global $wgCookieSecure;
	$oldSessionId = session_id();
	$cookieParams = session_get_cookie_params();
	if ( wfCheckEntropy() && $wgCookieSecure == $cookieParams['secure'] ) {
		session_regenerate_id( true ); // Wikia - $delete_old_session = true
	} else {
		$tmp = $_SESSION;
		session_destroy();
		wfSetupSession( MWCryptRand::generateHex( 32 ) );
		$_SESSION = $tmp;
	}
	$newSessionId = session_id();
	Hooks::run( 'ResetSessionID', array( $oldSessionId, $newSessionId ) );

	wfDebug( sprintf( "%s: new ID is '%s'\n", __METHOD__, $newSessionId ) );
}

/**
 * Initialise php session
 *
 * @param $sessionId Bool
 */
function wfSetupSession( $sessionId = false ) {
	global $wgSessionsInMemcached, $wgCookiePath, $wgCookieDomain,
			$wgCookieSecure, $wgCookieHttpOnly, $wgSessionHandler;

	if( $wgSessionsInMemcached ) {
		if ( !defined( 'MW_COMPILED' ) ) {
			global $IP;
			require_once( "$IP/includes/cache/MemcachedSessions.php" );
		}
		session_set_save_handler( 'memsess_open', 'memsess_close', 'memsess_read',
			'memsess_write', 'memsess_destroy', 'memsess_gc' );

		// It's necessary to register a shutdown function to call session_write_close(),
		// because by the time the request shutdown function for the session module is
		// called, $wgMemc has already been destroyed. Shutdown functions registered
		// this way are called before object destruction.
		register_shutdown_function( 'memsess_write_close' );
	} elseif( $wgSessionHandler && $wgSessionHandler != ini_get( 'session.save_handler' ) ) {
		# Only set this if $wgSessionHandler isn't null and session.save_handler
		# hasn't already been set to the desired value (that causes errors)
		ini_set( 'session.save_handler', $wgSessionHandler );
	}
	$httpOnlySafe = wfHttpOnlySafe() && $wgCookieHttpOnly;
	wfDebugLog( 'cookie',
		'session_set_cookie_params: "' . implode( '", "',
			array(
				0,
				$wgCookiePath,
				$wgCookieDomain,
				$wgCookieSecure,
				$httpOnlySafe ) ) . '"' );
	session_set_cookie_params( 0, $wgCookiePath, $wgCookieDomain, $wgCookieSecure, $httpOnlySafe );
	session_cache_limiter( 'private, must-revalidate' );
	if ( $sessionId ) {
		session_id( $sessionId );
	} else {
		wfFixSessionID();
	}
	wfSuppressWarnings();
	session_start();
	wfRestoreWarnings();

	// Wikia change - start
	// log all sessions started with 1% sampling (PLATFORM-1266)
	if ( ( new Wikia\Util\Statistics\BernoulliTrial( 0.01 ) )->shouldSample() ) {
		Wikia\Logger\WikiaLogger::instance()->info( __METHOD__, [
			'caller' => wfGetAllCallers(),
			'exception' => new Exception()
		] );
	}
	// Wikia change - end
}

/**
 * Get an object from the precompiled serialized directory
 *
 * @param $name String
 * @return Mixed: the variable on success, false on failure
 */
function wfGetPrecompiledData( $name ) {
	global $IP;

	$file = "$IP/serialized/$name";
	if ( file_exists( $file ) ) {
		$blob = file_get_contents( $file );
		if ( $blob ) {
			return unserialize( $blob );
		}
	}
	return false;
}

/**
 * Get a cache key
 *
 * @param varargs
 * @return String
 */
function wfMemcKey( /*... */ ) {
	global $wgCachePrefix;
	$prefix = $wgCachePrefix === false ? wfWikiID() : $wgCachePrefix;
	$args = func_get_args();
	$key = $prefix . ':' . implode( ':', $args );
	$key = str_replace( ' ', '_', $key );
	return $key;
}

/**
 * Get a cache key for a foreign DB
 *
 * @param $db String
 * @param $prefix String
 * @param varargs String
 * @return String
 */
function wfForeignMemcKey( $db, $prefix /*, ... */ ) {
	$args = array_slice( func_get_args(), 2 );
	if ( $prefix ) {
		$key = "$db-$prefix:" . implode( ':', $args );
	} else {
		$key = $db . ':' . implode( ':', $args );
	}
	return $key;
}

/**
 * Get an ASCII string identifying this wiki
 * This is used as a prefix in memcached keys
 *
 * @return String
 */
function wfWikiID() {
	global $wgDBprefix, $wgDBname;
	if ( $wgDBprefix ) {
		return "$wgDBname-$wgDBprefix";
	} else {
		return $wgDBname;
	}
}

/**
 * Split a wiki ID into DB name and table prefix
 *
 * @param $wiki String
 *
 * @return array
 */
function wfSplitWikiID( $wiki ) {
	$bits = explode( '-', $wiki, 2 );
	if ( count( $bits ) < 2 ) {
		$bits[] = '';
	}
	return $bits;
}

/**
 * Get a Database object.
 *
 * @param $db Integer: index of the connection to get. May be DB_MASTER for the
 *            master (for write queries), DB_SLAVE for potentially lagged read
 *            queries, or an integer >= 0 for a particular server.
 *
 * @param $groups Mixed: query groups. An array of group names that this query
 *                belongs to. May contain a single string if the query is only
 *                in one group.
 *
 * @param $wiki String: the wiki ID, or false for the current wiki
 *
 * Note: multiple calls to wfGetDB(DB_SLAVE) during the course of one request
 * will always return the same object, unless the underlying connection or load
 * balancer is manually destroyed.
 *
 * Note 2: use $this->getDB() in maintenance scripts that may be invoked by
 * updater to ensure that a proper database is being updated.
 *
 * @return DatabaseBase
 */
function &wfGetDB( $db, $groups = array(), $wiki = false ) {
	// wikia change begin -- SMW DB separation project, @author Krzysztof Krzyżaniak (eloy)
	global $smwgUseExternalDB, $wgDBname;
	if( $smwgUseExternalDB === true ) {
		if( ( is_array( $groups ) && in_array( 'smw', $groups ) ) || $groups === 'smw' ) {
			if( $wiki === false ) {
				$wiki = $wgDBname;
			}
			$wiki = "smw+" . $wiki;
			wfDebugLog( "connect", __METHOD__ . ": smw+ cluster is active, requesting $wiki\n", true );
		}
	}
	// wikia change end
	return wfGetLB( $wiki )->getConnection( $db, $groups, $wiki );
}

/**
 * Get a load balancer object.
 *
 * @param $wiki String: wiki ID, or false for the current wiki
 * @return LoadBalancer
 */
function wfGetLB( $wiki = false ) {
	return wfGetLBFactory()->getMainLB( $wiki );
}

/**
 * Get the load balancer factory object
 *
 * @return LBFactory
 */
function &wfGetLBFactory() {
	return LBFactory::singleton();
}

/**
 * Find a file.
 * Shortcut for RepoGroup::singleton()->findFile()
 *
 * @param $title String or Title object
 * @param $options Associative array of options:
 *     time:           requested time for an archived image, or false for the
 *                     current version. An image object will be returned which was
 *                     created at the specified time.
 *
 *     ignoreRedirect: If true, do not follow file redirects
 *
 *     private:        If true, return restricted (deleted) files if the current
 *                     user is allowed to view them. Otherwise, such files will not
 *                     be found.
 *
 *     bypassCache:    If true, do not use the process-local cache of File objects
 *
 * @return File, or false if the file does not exist
 */
function wfFindFile( $title, $options = array() ) {
	wfProfileIn(__METHOD__);

	if ( F::app()->wg->IsGhostVideo ) {
		wfProfileOut(__METHOD__);
		return false;
	}
	$file = RepoGroup::singleton()->findFile( $title, $options );
	wfProfileOut(__METHOD__);
	return $file;
}

/**
 * Get an object referring to a locally registered file.
 * Returns a valid placeholder object if the file does not exist.
 *
 * @param $title Title|String
 * @return LocalFile|null A File, or null if passed an invalid Title
 */
function wfLocalFile( $title ) {
	return RepoGroup::singleton()->getLocalRepo()->newFile( $title );
}

/**
 * Stream a file to the browser. Back-compat alias for StreamFile::stream()
 * @deprecated since 1.19
 */
function wfStreamFile( $fname, $headers = array() ) {
	wfDeprecated( __FUNCTION__, '1.19' );
	StreamFile::stream( $fname, $headers );
}

/**
 * Should low-performance queries be disabled?
 *
 * @return Boolean
 * @codeCoverageIgnore
 */
function wfQueriesMustScale() {
	global $wgMiserMode;
	return $wgMiserMode
		|| ( SiteStats::pages() > 100000
		&& SiteStats::edits() > 1000000
		&& SiteStats::users() > 10000 );
}

/**
 * Get the path to a specified script file, respecting file
 * extensions; this is a wrapper around $wgScriptExtension etc.
 *
 * @param $script String: script filename, sans extension
 * @return String
 */
function wfScript( $script = 'index' ) {
	global $wgScriptPath, $wgScriptExtension;
	return "{$wgScriptPath}/{$script}{$wgScriptExtension}";
}

/**
 * Get the script URL.
 *
 * @return script URL
 */
function wfGetScriptUrl() {
	if( isset( $_SERVER['SCRIPT_NAME'] ) ) {
		#
		# as it was called, minus the query string.
		#
		# Some sites use Apache rewrite rules to handle subdomains,
		# and have PHP set up in a weird way that causes PHP_SELF
		# to contain the rewritten URL instead of the one that the
		# outside world sees.
		#
		# If in this mode, use SCRIPT_URL instead, which mod_rewrite
		# provides containing the "before" URL.
		return $_SERVER['SCRIPT_NAME'];
	} else {
		return $_SERVER['URL'];
	}
}

/**
 * Convenience function converts boolean values into "true"
 * or "false" (string) values
 *
 * @param $value Boolean
 * @return String
 */
function wfBoolToStr( $value ) {
	return $value ? 'true' : 'false';
}

/**
 * Load an extension messages file
 *
 * @deprecated since 1.16, warnings in 1.18, remove in 1.20
 * @codeCoverageIgnore
 */
function wfLoadExtensionMessages() {
	wfDeprecated( __FUNCTION__, '1.16' );
}

/**
 * Get a platform-independent path to the null file, e.g. /dev/null
 *
 * @return string
 */
function wfGetNull() {
	return wfIsWindows()
		? 'NUL'
		: '/dev/null';
}

/**
 * Modern version of wfWaitForSlaves(). Instead of looking at replication lag
 * and waiting for it to go down, this waits for the slaves to catch up to the
 * master position. Use this when updating very large numbers of rows, as
 * in maintenance scripts, to avoid causing too much lag.  Of course, this is
 * a no-op if there are no slaves.
 *
 * Wikia note: provide external DB name in $wiki parameter to wait for external DB
 *
 * @param $wiki mixed Wiki identifier accepted by wfGetLB
 */
function wfWaitForSlaves( $wiki = false ) {
	$lb = wfGetLB( $wiki );
	// bug 27975 - Don't try to wait for slaves if there are none
	// Prevents permission error when getting master position
	if ( $lb->getServerCount() > 1 ) {
		/* Wikia change - added array() and $wiki parameters to getConnection to be able to wait for various DBs */
		$dbw = $lb->getConnection( DB_MASTER, array(), $wiki );
		$pos = $dbw->getMasterPos();
		$lb->waitForAll( $pos, $wiki );

		// Wikia change - begin
		// OPS-6313 - sleep-based implementaion for consul-powered DB clusters
		$masterHostName = $lb->getServerName(0);
		if ( strpos( $masterHostName, '.service.consul' ) !== false ) {
			# I am terribly sorry, but we do really need to wait for all slaves
			# not just the one returned by consul
			sleep( 1 );

			/* @var MySQLMasterPos $pos */
			\Wikia\Logger\WikiaLogger::instance()->info( 'wfWaitForSlaves for consul clusters',  [
				'exception' => new Exception(),
				'master' => $masterHostName,
				'pos' => $pos->__toString(),
				'wiki' => $wiki
			] );
		}
		// Wikia change - end
	}
}

/**
 * Used to be used for outputting text in the installer/updater
 * @deprecated since 1.18, warnings in 1.18, remove in 1.20
 */
function wfOut( $s ) {
	wfDeprecated( __FUNCTION__, '1.18' );
	global $wgCommandLineMode;
	if ( $wgCommandLineMode ) {
		echo $s;
	} else {
		echo htmlspecialchars( $s );
	}
	flush();
}

/**
 * Count down from $n to zero on the terminal, with a one-second pause
 * between showing each number. For use in command-line scripts.
 * @codeCoverageIgnore
 * @param $n int
 */
function wfCountDown( $n ) {
	for ( $i = $n; $i >= 0; $i-- ) {
		if ( $i != $n ) {
			echo str_repeat( "\x08", strlen( $i + 1 ) );
		}
		echo $i;
		flush();
		if ( $i ) {
			sleep( 1 );
		}
	}
	echo "\n";
}

/**
 * Generate a random 32-character hexadecimal token.
 * @param $salt Mixed: some sort of salt, if necessary, to add to random
 *              characters before hashing.
 * @return string
 * @codeCoverageIgnore
 */
function wfGenerateToken( $salt = '' ) {
	$salt = serialize( $salt );
	return md5( mt_rand( 0, 0x7fffffff ) . $salt );
}

/**
 * Replace all invalid characters with -
 *
 * @param $name Mixed: filename to process
 * @return String
 */
function wfStripIllegalFilenameChars( $name ) {
	global $wgIllegalFileChars;
	$name = wfBaseName( $name );
	$name = preg_replace(
		"/[^" . Title::legalChars() . "]" .
			( $wgIllegalFileChars ? "|[" . $wgIllegalFileChars . "]" : '' ) .
			"/",
		'-',
		$name
	);
	return $name;
}

/**
 * Set PHP's memory limit to the larger of php.ini or $wgMemoryLimit;
 *
 * @return Integer value memory was set to.
 */
function wfMemoryLimit() {
	global $wgMemoryLimit;
	$memlimit = wfShorthandToInteger( ini_get( 'memory_limit' ) );
	if( $memlimit != -1 ) {
		$conflimit = wfShorthandToInteger( $wgMemoryLimit );
		if( $conflimit == -1 ) {
			wfDebug( "Removing PHP's memory limit\n" );
			wfSuppressWarnings();
			ini_set( 'memory_limit', $conflimit );
			wfRestoreWarnings();
			return $conflimit;
		} elseif ( $conflimit > $memlimit ) {
			wfDebug( "Raising PHP's memory limit to $conflimit bytes\n" );
			wfSuppressWarnings();
			ini_set( 'memory_limit', $conflimit );
			wfRestoreWarnings();
			return $conflimit;
		}
	}
	return $memlimit;
}

/**
 * Converts shorthand byte notation to integer form
 *
 * @param $string String
 * @return Integer
 */
function wfShorthandToInteger( $string = '' ) {
	$string = trim( $string );
	if( $string === '' ) {
		return -1;
	}
	$last = $string[strlen( $string ) - 1];
	$val = intval( $string );
	switch( $last ) {
		case 'g':
		case 'G':
			$val *= 1024;
			// break intentionally missing
		case 'm':
		case 'M':
			$val *= 1024;
			// break intentionally missing
		case 'k':
		case 'K':
			$val *= 1024;
	}

	return $val;
}

/**
 * Get the normalised IETF language tag
 * See unit test for examples.
 *
 * @param $code String: The language code.
 * @return String: The language code which complying with BCP 47 standards.
 */
function wfBCP47( $code ) {
	$codeSegment = explode( '-', $code );
	$codeBCP = array();
	foreach ( $codeSegment as $segNo => $seg ) {
		if ( count( $codeSegment ) > 0 ) {
			// when previous segment is x, it is a private segment and should be lc
			if( $segNo > 0 && strtolower( $codeSegment[( $segNo - 1 )] ) == 'x' ) {
				$codeBCP[$segNo] = strtolower( $seg );
			// ISO 3166 country code
			} elseif ( ( strlen( $seg ) == 2 ) && ( $segNo > 0 ) ) {
				$codeBCP[$segNo] = strtoupper( $seg );
			// ISO 15924 script code
			} elseif ( ( strlen( $seg ) == 4 ) && ( $segNo > 0 ) ) {
				$codeBCP[$segNo] = ucfirst( strtolower( $seg ) );
			// Use lowercase for other cases
			} else {
				$codeBCP[$segNo] = strtolower( $seg );
			}
		} else {
		// Use lowercase for single segment
			$codeBCP[$segNo] = strtolower( $seg );
		}
	}
	$langCode = implode( '-', $codeBCP );
	return $langCode;
}

/**
 * Get a cache object.
 *
 * @param $inputType integer Cache type, one the the CACHE_* constants.
 * @return BagOStuff
 */
function wfGetCache( $inputType ) {
	return ObjectCache::getInstance( $inputType );
}

/**
 * Get the main cache object
 *
 * @return BagOStuff
 */
function wfGetMainCache() {
	global $wgMainCacheType;
	return ObjectCache::getInstance( $wgMainCacheType );
}

/**
 * Get the cache object used by the message cache
 *
 * @return BagOStuff
 */
function wfGetMessageCacheStorage() {
	global $wgMessageCacheType;
	return ObjectCache::getInstance( $wgMessageCacheType );
}

/**
 * Get the cache object used by the parser cache
 *
 * @return BagOStuff
 */
function wfGetParserCacheStorage() {
	global $wgParserCacheType;
	return ObjectCache::getInstance( $wgParserCacheType );
}

/**
 * Call hook functions defined in $wgHooks
 *
 * @param $event String: event name
 * @param $args Array: parameters passed to hook functions
 * @return Boolean True if no handler aborted the hook
 */
function wfRunHooks( $event, $args = array() ) {
	return Hooks::run( $event, $args );
}

/**
 * Wrapper around php's unpack.
 *
 * @param $format String: The format string (See php's docs)
 * @param $data: A binary string of binary data
 * @param $length integer or false: The minimun length of $data. This is to
 *	prevent reading beyond the end of $data. false to disable the check.
 *
 * Also be careful when using this function to read unsigned 32 bit integer
 * because php might make it negative.
 *
 * @throws MWException if $data not long enough, or if unpack fails
 * @return Associative array of the extracted data
 */
function wfUnpack( $format, $data, $length=false ) {
	if ( $length !== false ) {
		$realLen = strlen( $data );
		if ( $realLen < $length ) {
			throw new MWException( "Tried to use wfUnpack on a "
				. "string of length $realLen, but needed one "
				. "of at least length $length."
			);
		}
	}

	wfSuppressWarnings();
	$result = unpack( $format, $data );
	wfRestoreWarnings();

	if ( $result === false ) {
		// If it cannot extract the packed data.
		throw new MWException( "unpack could not unpack binary data" );
	}
	return $result;
}

/**
 * Get a random string containing a number of pseudo-random hex
 * characters.
 * @note This is not secure, if you are trying to generate some sort
 *       of token please use MWCryptRand instead.
 *
 * @param int $length The length of the string to generate
 * @return string
 */
function wfRandomString( $length = 32 ) {
	$str = '';
	for ( $n = 0; $n < $length; $n += 7 ) {
		$str .= sprintf( '%07x', mt_rand() & 0xfffffff );
	}
	return substr( $str, 0, $length );
}
