<div id="top_bar">
  <img src="/logo.png" id="google_logo" alt="Google Logo">
  <div id="search_div">
    <div id="search_input" contenteditable="true"><?= ($snippets->getQuery()) ?></div>
    <div id="search_categories">
      <div class="search_cat_active">Alle Kategorien</div>
      <div class="search_cat" style="margin-left: 50px;">Images</div>
      <div class="search_cat">Videos</div>
      <div class="search_cat">News</div>
      <div class="search_cat">Maps</div>
      <div class="search_cat">More</div>
    </div>
  </div>
</div>