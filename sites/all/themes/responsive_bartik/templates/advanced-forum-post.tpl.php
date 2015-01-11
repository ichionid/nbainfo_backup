<?php

/**
 * @file
 *
 * Theme implementation: Template for each forum post whether node or comment.
 *
 * All variables available in node.tpl.php and comment.tpl.php for your theme
 * are available here. In addition, Advanced Forum makes available the following
 * variables:
 *
 * - $top_post: TRUE if we are formatting the main post (ie, not a comment)
 * - $reply_link: Text link / button to reply to topic.
 * - $total_posts: Number of posts in topic (not counting first post).
 * - $new_posts: Number of new posts in topic, and link to first new.
 * - $links_array: Unformatted array of links.
 * - $account: User object of the post author.
 * - $name: User name of post author.
 * - $author_pane: Entire contents of the Author Pane template.
 */
?>
<?php if (!$is_front): 
  if ($top_post): 
  ?>
  <div class="forum-right-side">
    <?php endif; ?>
    <div id="<?php print $post_id; ?>" class="<?php print $classes; ?>" <?php print $attributes; ?>>
      <div class="forum-post-info clearfix">
        <div class="forum-posted-on">
          <?php print $date ?>
          <?php if (!$top_post): ?>
            <?php if (!empty($new)): ?>
              <a id="new"><span class="new">(<?php print $new ?>)</span></a>
            <?php endif; ?>
            <?php if (!empty($first_new)): ?>
              <?php print $first_new; ?>
            <?php endif; ?>
            <?php if (!empty($new_output)): ?>
              <?php print $new_output; ?>
            <?php endif; ?>
          <?php endif; ?>
        </div> 
      	<?php if (!empty($in_reply_to)): ?>
     	 <span class="forum-in-reply-to"><?php print $in_reply_to; ?></span>
      	<?php endif; ?>
        <?php if (!$node->status): ?>
          <span class="unpublished-post-note"><?php print t("Unpublished post") ?></span>
        <?php endif; ?>

        <span class="forum-post-number"><?php print $permalink; ?></span>
      </div>
      <div class="forum-post-wrapper">
        <div class="forum-post-panel-sub">
          <?php if (!empty($author_pane) && !$top_post): ?>
            <?php print $author_pane; ?>
          <?php endif; ?>
        </div>
        <div class="forum-post-panel-main clearfix">
          <?php if (!empty($title)): ?>
            <div class="forum-post-title">
              <?php print $title;?>
            </div>
          <?php endif; ?>
          <div class="forum-post-content">
            <?php 
            if(isset($content['taxonomy_forums']['#object']->taxonomy_forums['und'][0]['taxonomy_term']->name)) {
              $topic = $content['taxonomy_forums']['#object']->taxonomy_forums['und'][0]['taxonomy_term']->name;
              watchdog('topic', $topic);
              hide($content['taxonomy_forums']);
              hide($content['comments']);
              hide($content['links']);
              if($topic!='Spillere') {
               // watchdog('Not player', '1');
                hide($content['field_player']);
                hide($content['field_embed_player_s_stats_link']);
              }
              if($topic!='Hold') {
                //watchdog('Not team', '1');
	        hide($content['field_team']);
	      }
	      if(($topic=='Hold') || ($topic=='Spillere')) {
                //watchdog('Not Discussion', '1');
                hide($content['field_topic_description']);
              }
            }
            //taxonomy_forums
            if (!$top_post){
              //watchdog('Not Discussion', 'HIDE BODY');            
              hide($content['body']);
	    }
            hide($content['taxonomy_forums']);
            hide($content['comments']);
            hide($content['links']);
            print render($content);
          ?>
          <div class="topic-related-articles">
          </div>
        </div>
        </div>

        <?php if (!empty($post_edited)): ?>
          <div class="post-edited">
            <?php print $post_edited ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($signature)): ?>
          <div class="author-signature">
            <?php print $signature ?>
          </div>
        <?php endif; ?>
      </div>
    </div> <?php // End of post wrapper div ?>
    <div class="forum-post-footer clearfix">
      <div class="forum-jump-links">
        <a href="#forum-topic-top" title="<?php print t('Jump to top of page'); ?>" class="af-button-small"><span><?php print t("Top"); ?></span></a>
      </div>

      <div class="forum-post-links">
        <?php print render($content['links']); ?>
      </div>
    </div> <?php // End of footer div ?>
  </div> <?php // End of main wrapping div ?>
  <?php if ($top_post): ?>
  </div> <?php // End of forum-right-side ?>
  <?php endif; ?>

  <div class="forum-left-side">
  <?php 
    print render($content['comments']);
   ?>
  </div>
<?php endif; ?>
























<?php if ($is_front): 
    watchdog('FRONT', 'A');
?>
<div class="forum-comment">
  <div class="forum-comment-header-wrapper">
    <?php print '<div class="forum-subject-title">' . render($node->title) . '</div>';?>
    <?php print '<div class="forum-comment-date">' . $changed . '</div>';?>
  </div>
  <!--//Debatten titel , date af comment-->
  <div class="forum-comment-content">
    <div class="forum-left-side">
    <!--//User photo -->
    <?php 
      $user = user_load($comment->uid);
      print theme_image_style(
        array(
          'width' => '100',
          'height' => '100',
          'style_name' => 'thumbnail',
          'path' => (isset($user->picture->uri) ? $user->picture->uri : 'default.icon'),
        )
      );
    ?>
    </div>
    <div class="forum-right-side">
      <div class="forum-right-username">
        <?php 
          $options = array('absolute' => TRUE);
          $url = url('user/' . $comment->uid, $options);
          print l($user->name, $url);
        ?> 
      </div>
      <div class="forum-comment-actual-content">
        <?php
          if (isset($comment->comment_body['und'][0]['safe_value'])) {
            print $comment->comment_body['und'][0]['safe_value'];
          } else {
            print $comment->comment_body['subject'];
          }
        ?>
      </div>
      <div class="forum-read-more">
        <?php 
          $options = array('absolute' => TRUE);
          $url = url('node/' . $comment->nid, $options);
          print l(t('Læs mere og byd ind i debatten'), $url, array('attributes' => array('class' => 'read-more')));
        ?> 
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
