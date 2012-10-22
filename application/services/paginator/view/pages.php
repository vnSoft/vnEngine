<div class="pager">

    <? if ($this->iPage > 1) { ?>
        <a href="<?= "{$this->sLink}/page/" . ($this->iPage - 1) . "/"; ?>"> < </a>
    <? } ?>

    <?
    $iStart = $this->iPage - 3;
    if ($iStart < 1)
        $iStart = 1;

    $iEnd = $this->iPage + 3;
    if ($iEnd > $this->iPageCount)
        $iEnd = $this->iPageCount;



    for ($i = $iStart; $i <= $iEnd; $i++) {
        if ($i == $this->iPage)
            echo "<a id='current_page' href='{$this->sLink}/page/$i/'>$i</a>";
        else
            echo "<a href='{$this->sLink}/page/$i/'>$i</a>";
    }
    ?>
    <? if ($this->iPage < $this->iPageCount) { ?>
        <a href="<?= "{$this->sLink}/page/" . ($this->iPage + 1) . "/"; ?>"> > </a>
    <? } ?>

   <? if ($iEnd < $this->iPageCount) { ?>
        <span>z</span>
        <a href='<?= "{$this->sLink}/page/{$this->iPageCount}/"; ?>'><?= $this->iPageCount; ?></a>
   <? } ?>

</div>