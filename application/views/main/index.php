<?php if ($address): ?>
    <div id="search-key" class="search-performed">TWEET ABOUT <?php echo htmlentities($address) ?></div>
<?php else: ?>
    <div id="search-key" class="search-performed"></div>
<?php endif ?>
<div id="map-canvas"></div>
<div id="control-holder">
    <form id="address-form">
        <div id="input-holder">
            <div id="input-button-indent">
                <input type="text" id="address" name="address" class="input-box input-pad" autocomplete="off" 
                    placeholder="City name" value="<?php echo !empty($address) ? $address : '' ?>" />
            </div>
        </div>
        <div id="button-holder">
            <input type="submit" class="button float-left input-pad" id="search" value="SEARCH" />
            <input type="button" class="button float-left input-pad" value="HISTORY" onclick="window.location.href='history'" />
        </div>
    </form>
</div>