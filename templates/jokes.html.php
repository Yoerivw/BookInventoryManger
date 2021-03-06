<div class="jokelist">
<ul class="categories">
<?php foreach ($categories as $category): ?>
<li><a href="/joke/list?category=
<?=$category->id?>">
<?=$category->name?></a><li>
<?php endforeach; ?>
</ul>




<p><?= $totalJokes?> Jokes have been submitted to the internet Joke Database.</p>

<?php foreach($jokes as $joke): ?>

    <blockquote>
    
    <p>
  <?=htmlspecialchars($joke->joketext, ENT_QUOTES, 'UTF-8')?>

    (by <a href="mailto:<?=htmlspecialchars($joke->getAuthor()->email, ENT_QUOTES,
                    'UTF-8'); ?>">
                <?=htmlspecialchars($joke->getAuthor()->name, ENT_QUOTES,
                    'UTF-8'); ?></a> on 
<?php
$date = new DateTime($joke->jokedate);

echo $date->format('jS F Y');
?>)

<?php if($user): ?>
  <?php if($user->id == $joke->authorid || $user->hasPermission(\Ijdb\Entity\Author::EDIT_JOKES)): ?>
    <a href="/joke/edit?id=<?=$joke->id;?>">Edit</a>
  <?php endif; ?>

  <?php if($user->id == $joke->authorid || $user->hasPermission(\Ijdb\Entity\Author::DELETE_JOKES)):?>
    <form action="/joke/delete" method="post">
      <input type="hidden" name="id" value="<?=$joke->id;?>">
      <input type="submit" value="Delete">
    </form>
  <?php endif;?>
  <?php endif;?>
  </p>
      
    </blockquote>
    
<?php endforeach; 

?>