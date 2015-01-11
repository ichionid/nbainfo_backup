<?php
/**
 * This tpl handles the like link and its look and feel.
 * variables avaiable:
 * @id: the node id of the node/comment on which the link is getting printed.
 * @likes: the number is likes that is casted to the node/comment.
 */
$path = base_path() . drupal_get_path("module","likedislike");
?>
<div class="like-container-<?php print $entity ?>" id="like-container-<?php print $eid; ?>">
    <div class="like inline float-left">
        <?php if ($likestatus == 0): ?>
            <a href="javascript:;" nodeid="<?php print $eid; ?>" class="<?php print $entity ?>"><img src="<?php print $path ?>/images/like.gif" alt="Like" title="Like" class="<?php print $entity ?>"></a>
        <?php endif; ?>
        <?php if ($likestatus == 1): ?>
            <a href="javascript:;" nodeid="<?php print $eid; ?>" class="disable-status <?php print $entity ?>"><img src="<?php print $path ?>/images/likeAct.gif" alt="Like" title="Like" class="<?php print $entity ?>"></a>
        <?php endif; ?>
    </div>
    <div class="float-left like-count-<?php print $entity ?>"><?php print $likes; ?></div>
</div>
