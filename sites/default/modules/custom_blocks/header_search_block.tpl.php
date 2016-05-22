<div role="search" class="block block-search contextual-links-region no-title" id="block-search-form">
    <div class="block-inner clearfix">
        <div class="block-content content">
            <div class="container-inline">
                <div class="form-item form-type-textfield form-item-search-block-form">
                    <label for="edit-search-block-form--2" class="element-invisible">Search </label>
                    <input type="search" class="form-text" maxlength="128" size="15" value="<?php 
                    if (arg(0) == "search" && arg(1) == "google" && arg(2)) print arg(2);?>" name="search_block_form" id="edit-search-block-form--2" title="Enter the terms you wish to search for.">
                </div>

                <div id="edit-actions" class="form-actions form-wrapper">
                    <input type="submit" class="form-submit" value="" name="op" id="edit-submit">
                </div>

                <div class="language-icons">
                    <a class="mail-button" href="<?php global $base_path; print $base_path.'inquiry'; ?>"></a>
                    <?php
                    global $language;
                    if ($language->language == "fr") {
                        print '<a title="French" href="?language=en" class="english-language"></a>';
                    } else {
                        print '<a title="French" href="?language=fr" class="french-language"></a>';
                    }
                    ?>
                    <a title="Germany" href="http://www.erlebe-indochina.de/home.html" target="_blank" class="germany-language"></a>
                    <a title="Vietnam" href="http://www.lukhach24h.com/" target="_blank" class="vietnam-language"></a>
                </div>
            </div>
        </div>
    </div>
</div>

