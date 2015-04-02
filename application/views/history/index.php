<ul id="menu-item">
    <li class="item"><a href="<?php echo base_url() ?>"> &lt; BACK TO THE TWEETS</a></li>
    <?php foreach ($savedSearch as $search): ?>
        <li class="item"><a href="<?php echo base_url().'?q='.$search->search_text ?>"><?php echo htmlentities($search->search_text) ?></a></li>
    <?php endforeach ?>
</ul>
