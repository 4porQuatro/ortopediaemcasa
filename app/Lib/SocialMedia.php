<?php

namespace App\Lib;

abstract class SocialMedia
{
	/**
	 *	Generates the Facebook share link
	 *
	 *	@param string $url				The page url
	 *
	 *	@return string $share_url
	 */
	public static function shareFacebookURL($url)
	{
		return 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url);
	}

	/**
	 *	Generates the Pinterest share link
	 *
	 *	@param string $url				The page url
	 *
	 *	@return string $share_url
	 */
	public static function sharePinterestURL($url, $image_url, $description)
	{
		return 'http://pinterest.com/pin/create/button/?url=' . urlencode($url) . '&media=' . urlencode($image_url) . '&description=' . urlencode($description);
	}

	/**
	 *	Generates the Twitter share link
	 *
	 *	@param string $url				The page url
	 *	@param string $title			The SEO title
	 *
	 *	@return string $share_url
	 */
	public static function shareTwitterURL($url, $title)
	{
		return 'http://twitter.com/home?status=' . urlencode($title) . '+' . urlencode($url);
	}

	/**
	 *	Generates the Linkedin share link
	 *
	 *	@param string $url				The page url
	 *	@param string $title			The SEO title
	 *	@param string $description		The SEO description
	 *	@param string $source			The application name
	 *
	 *	@return string $share_url
	 */
	public static function shareLinkedinURL($url, $title, $description, $source)
	{
		return 'https://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($url) . '&amp;title=' . urlencode($title) . '&amp;summary=' . urlencode($description) . '&amp;source=' . urlencode($source);
	}
}
