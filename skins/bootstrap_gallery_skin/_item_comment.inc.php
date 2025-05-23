<?php
/**
 * This is the template that displays a single comment
 *
 * This file is not meant to be called directly.
 *
 * b2evolution - {@link http://b2evolution.net/}
 * Released under GNU GPL License - {@link http://b2evolution.net/about/gnu-gpl-license}
 * @copyright (c)2003-2020 by Francois Planque - {@link http://fplanque.com/}
 *
 * @package evoskins
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );


// Default params:
$params = array_merge( array(
		'comment_start'         => '<article class="evo_comment panel panel-default">',
		'comment_end'           => '</article>',
		'comment_post_display'	=> false,	// Do we want ot display the title of the post we're referring to?
		'comment_post_before'   => '<h3 class="evo_comment_post_title">',
		'comment_post_after'    => '</h3>',
		'comment_title_before'  => '<div class="panel-heading"><h4 class="evo_comment_title panel-title">',
		'comment_title_after'   => '</h4></div><div class="panel-body">',
		'comment_avatar_before' => '<span class="evo_comment_avatar">',
		'comment_avatar_after'  => '</span>',
		'comment_rating_before' => '<div class="evo_comment_rating">',
		'comment_rating_after'  => '</div>',
		'comment_text_before'   => '<div class="evo_comment_text">',
		'comment_text_after'    => '</div>',
		'comment_info_before'   => '<div class="evo_comment_footer clear text-muted"><small>',
		'comment_info_after'    => '</small></div></div>',
		'comment_footer_before'  => '<footer class="panel-footer"><div class="text-muted small">',
		'comment_footer_after'  => '</div></footer>',
		'link_to'               => 'userurl>userpage', // 'userpage' or 'userurl' or 'userurl>userpage' or 'userpage>userurl'
		'author_link_text'      => 'auto', // avatar_name | avatar_login | only_avatar | name | login | nickname | firstname | lastname | fullname | preferredname
		'before_image'          => '<figure class="evo_image_block">',
		'before_image_legend'   => '<figcaption class="evo_image_legend">',
		'after_image_legend'    => '</figcaption>',
		'after_image'           => '</figure>',
		'image_size'            => 'fit-1280x720',
		'image_class'           => 'img-responsive',
		'Comment'               => NULL, // This object MUST be passed as a param!
	), $params );

/**
 * @var Comment
 */
$Comment = & $params['Comment'];

// Load comment's Item object:
$Comment->get_Item();


$Comment->anchor();
echo update_html_tag_attribs( $params['comment_start'], array(
	'class' => 'vs_'.$Comment->status.( $Comment->is_meta() ? ' evo_comment__meta' : '' ), // Add style class for proper comment status
	'id'    => 'comment_'.$Comment->ID // Add id to know what comment is used on AJAX status changing
), array( 'id' => 'skip' ) );

// Post title
if( $params['comment_post_display'] )
{
	echo $params['comment_post_before'];
	echo T_('In response to').': ';
	$Comment->Item->title( array(
			'link_type' => 'permalink',
		) );
	echo $params['comment_post_after'];
}

// Title
echo $params['comment_title_before'];
switch( $Comment->get( 'type' ) )
{
	case 'comment': // Display a comment:
	case 'meta': // Display an internal comment:
		if( $Comment->is_meta() )
		{	// Internal comment:
			echo '<span class="badge badge-info">'.$Comment->get_inlist_order().'</span> ';
		}

		if( empty($Comment->ID) )
		{	// PREVIEW comment
			echo '<span class="evo_comment_type_preview">'.T_('PREVIEW Comment from:').'</span> ';
		}
		else
		{	// Normal comment
			$Comment->permanent_link( array(
					'before'    => '',
					'after'     => ' '.T_('from:').' ',
					'text'      => T_('Comment'),
					'class'		=> 'evo_comment_type',
					'nofollow'  => true,
				) );
		}

		$Comment->author2( array(
				'before'       => ' ',
				'after'        => '#',
				'before_user'  => '',
				'after_user'   => '#',
				'format'       => 'htmlbody',
				'link_to'      => $params['link_to'],		// 'userpage' or 'userurl' or 'userurl>userpage' or 'userpage>userurl'
				'link_text'    => $params['author_link_text'],
			) );

		if( ! $Comment->get_author_User() )
		{ // Display action icon to message only if this comment is from a visitor
			$Comment->msgform_link( $Blog->get( 'msgformurl' ) );
		}
		break;

	case 'trackback': // Display a trackback:
		$Comment->permanent_link( array(
				'before'    => '',
				'after'     => ' '.T_('from:').' ',
				'text' 		=> T_('Trackback'),
				'class'		=> 'evo_comment_type',
				'nofollow'	=> true,
			) );
		$Comment->author( '', '#', '', '#', 'htmlbody', true, $params['author_link_text'] );
		break;

	case 'pingback': // Display a pingback:
		$Comment->permanent_link( array(
				'before'    => '',
				'after'     => ' '.T_('from:').' ',
				'text' 		=> T_('Pingback'),
				'class'		=> 'evo_comment_type',
				'nofollow'	=> true,
			) );
		$Comment->author( '', '#', '', '#', 'htmlbody', true, $params['author_link_text'] );
		break;

	case 'webmention': // Display a webmention:
		$Comment->permanent_link( array(
				'before'   => '',
				'after'    => ' '.T_('from:').' ',
				'text'     => T_('Webmention'),
				'class'    => 'evo_comment_type',
				'nofollow' => true,
			) );
		$Comment->author( '', '#', '', '#', 'htmlbody', true, $params['author_link_text'] );
		break;
}

// Status
if( $Comment->status != 'published' )
{ // display status of comment (typically an angled banner in the top right corner):
	$Comment->format_status( array(
			'template' => '<div class="evo_status evo_status__$status$ badge pull-right" data-toggle="tooltip" data-placement="top" title="$tooltip_title$">$status_title$</div>',
		) );
}

echo $params['comment_title_after'];

// Avatar:
echo $params['comment_avatar_before'];
$Comment->avatar();
echo $params['comment_avatar_after'];

// Rating:
$Comment->rating( array(
		'before' => $params['comment_rating_before'],
		'after'  => $params['comment_rating_after'],
	) );

// Text:
echo $params['comment_text_before'];
$Comment->content( 'htmlbody', false, true, $params );
echo $params['comment_text_after'];

// Info:
echo $params['comment_info_before'];

$commented_Item = & $Comment->get_Item();
$comment_redirect_url = $Comment->get_permanent_url();

echo '<span class="pull-left">';
$Comment->reply_link(); /* Link for replying to the Comment */
$Comment->vote_helpful( '', '', '&amp;', true, true );
echo '</span>';

echo '<div class="action_btn_group">';
	$Comment->edit_link( ' ', '', '#', T_('Edit this reply'), button_class( 'text' ).' comment_edit_btn', '&amp;', true, $comment_redirect_url ); /* Link for editing */
	echo '<span class="'.button_class( 'group' ).'">';
		$delete_button_is_displayed = check_user_perm( 'comment!CURSTATUS', 'delete', false, $Comment );
		$Comment->moderation_links( array(
			'text' => '#',
			'ajax_button' => true,
			'class'       => button_class( 'text' ),
			'redirect_to' => $comment_redirect_url,
			'detect_last' => !$delete_button_is_displayed,
		) );
		$Comment->delete_link( '', '', '#', T_('Delete this reply'), button_class( 'text' ), false, '&amp;', true, false, '#', $commented_Item->get_permanent_url() ); /* Link to backoffice for deleting */
	echo '</span>';
echo '</div>';

echo $params['comment_info_after'];

echo $params['comment_footer_before'];
$Comment->date(); echo ' @ '; $Comment->time( '#short_time' );
echo $params['comment_footer_after'];

echo $params['comment_end'];
?>