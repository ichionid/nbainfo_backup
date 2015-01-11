<?php
/**
 * This tpl handles the like link and its look and feel.
 * variables avaiable:
 * @id: the node id of the node/comment on which the link is getting printed.
 * @likes: the number is likes that is casted to the node/comment.
 */
$path = base_path() . drupal_get_path("module","likedislike");
?>
<div class="dislike-container-<?php print $entity ?>" id="dislike-container-<?php print $eid; ?>">
    <div class="dislike inline float-left">
        <?php if ($dislikestatus == 0): ?>
            <a href="javascript:;" nodeid="<?php print $eid; ?>" class="<?php print $entity ?>"><img src="<?php print $path ?>/images/dislike.gif" alt="Dislike" title="Dislike" class="<?php print $entity ?>"></a>
        <?php endif; ?>
        <?php if ($dislikestatus == 1): ?>
            <a href="javascript:;" nodeid="<?php print $eid; ?>" class="disable-status <?php print $entity ?>"><img src="<?php print $path ?>/images/dislikeAct.gif" alt="Dislike" title="Dislike" class="<?php print $entity ?>"></a>
        <?php endif; ?>
    </div>
    <div class="float-left dislike-count-<?php print $entity ?>"><?php print $dislikes; ?></div>
</div>
