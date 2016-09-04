<?php
/**
 * Get markdown text, process through http://www.artlebedev.ru/tools/typograf/webservice/
 * and outputs html text and text for facebook/vacancies
 */
 ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Typograf and Markdown to HTML and Plain Text</title>
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
  <style>
  body {
    margin: 0;
    font: 72% sans-serif;
  }

  a {
    color:#008ACE;
  }
  /**
   * For modern browsers
   * 1. The space content is one way to avoid an Opera bug when the
   *    contenteditable attribute is included anywhere else in the document.
   *    Otherwise it causes space to appear at the top and bottom of elements
   *    that are clearfixed.
   * 2. The use of `table` rather than `block` is only necessary if using
   *    `:before` to contain the top-margins of child elements.
   */
.group:before,
.group:after {
    content: " "; /* 1 */
    display: table; /* 2 */
}

.group:after {
    clear: both;
}

/**
 * For IE 6/7 only
 * Include this rule to trigger hasLayout and contain floats.
 */
.group {
    *zoom: 1;
}

.mainheader {
  margin-bottom: 2em;
  padding: 30px;
  background: #fafafa;
  border-bottom: 1px solid #cecece;
}

.wrapper {
  margin:30px;
}

.main {
  float:left;
  width:78%;
}

.sidebar {
  float:right;
  width:19.9%;
  font-size:small;
}

.sidebar h1 { 
    font-size: 1.5em;
    font-weight: bold;
  }
  .sidebar h2 { 
    font-size: 1.2em;
    font-weight: bold;
    margin-bottom: -.5em;
  }
  .sidebar h3 { 
    font-size: 1em;
    font-weight: bold;
    text-transform: none;
    margin-bottom: .25em;
    margin-top: 1.5em;
  }
  .sidebar code { 
    font-family: Monaco, ProFont, "Andale Mono", "Lucida Console", Courier, monospace;
    font-size: 10px;
  }
  .sidebar pre {  
    line-height: 12px;
    margin-top: 0;
    background-color: #f5f5f5;
    border: 1px solid #ccc;
    padding: 4px;
  }
  .sidebar p {  
    margin-top: 0;
    margin-bottom: 0;
  }

  footer {
    margin:20px 0;
    padding:30px;
    border-top: solid 1px #cecece;
  }

  </style>

</head>
<body>
  <header class="mainheader">
    <h1><a href="http://daringfireball.net/projects/markdown/dingus">Markdown</a> + <a href="http://www.artlebedev.ru/tools/typograf/">Типограф</a></h1>
    <p>Подготовка текстов для интернета. Поддерживается синтаксис <a href="http://daringfireball.net/projects/markdown/syntax">Markdown</a> для разметки элементов. После этого текст обрабатывается <a href="http://www.artlebedev.ru/tools/typograf/">Типографом</a> для расстановки красивых кавычек, тире и неразрывных пробелов.</p>
    <p>В левом блоке отображается результат в виде html, в правом — простой текст с вырезанными тегами и поддержкой списков, которые форматируются длинным тире в качестве маркера.</p>
    <p>Вот так: </p>
    <p>— Foo<br>
    — Bar</p>
  </header>


  <?
    $text = stripslashes ($_POST[text]);
    if (!$text) $text = '"Вы все еще кое-как верстаете в "Ворде"? - Тогда мы идем к вам!"';
  ?>
  <div class="group wrapper">
  <div class="main">
    <form method="post">
      <h2><label for="text">Markdown text</label></h2>
      <textarea style="width: 99%; height: 300px" name="text" id="text"><? echo $text; ?></textarea>
      <p>
        <button class="process-button" type="submit">Process text</button>
      </p>   
    </form>

    <div class="group">
    <div style="width:48%;float:left;">
    <?
      if ($_POST[text])
      {
        include_once "php-markdown/markdown.php";
        include "typograf/remotetypograf.php";

        $result = Markdown($text);

        $text = "\n" . $text;
        $fb_result = preg_replace("/\n(\s+)?\* /m", "\n— ", $text);
        $fb_result = preg_replace("/#+\s+?/", "", $fb_result);
        
        $remoteTypograf = new RemoteTypograf();

        $remoteTypograf->noEntities();
        $remoteTypograf->br (true);
        $remoteTypograf->p (false);
        $remoteTypograf->nobr (3);
        $remoteTypograf->quotA ('laquo raquo');
        $remoteTypograf->quotB ('bdquo ldquo');

        $result =  $remoteTypograf->processText ($result);
        $markdown_result = str_replace ("\n\n", "\n", $result);
       
        $fb_result = $remoteTypograf->processText ($fb_result);
        $fb_result = strip_tags(trim($fb_result));
      }
    ?>
    <h3><label for="result">HTML</label></h3>
    
    <textarea style="width: 99%; height: 300px" name="markdown_result" id="markdown_result"><? echo $markdown_result; ?></textarea>
    </div>

    <div style="width:48%;float:right;">
    <h3><label for="markdown_result">Plain text</label></h3>
    <textarea style="width: 99%; height: 300px" name="result" id="result"><? echo $fb_result; ?></textarea>
    </div>
  </div>

  <h3>Результат</h3>
  <div id="final_result"><? echo $markdown_result; ?></div>


  </div>



<div class="sidebar">
<h1>Markdown</h1>

<h2>Syntax Cheatsheet:</h2>

<h3>Phrase Emphasis</h3>

<pre><code>*italic*   **bold**
_italic_   __bold__
</code></pre>

<h3>Links</h3>

<p>Inline:</p>

<pre><code>An [example](http://url.com/ "Title")
</code></pre>

<p>Reference-style labels (titles are optional):</p>

<pre><code>An [example][id]. Then, anywhere
else in the doc, define the link:

  [id]: http://example.com/  "Title"
</code></pre>

<h3>Images</h3>

<p>Inline (titles are optional):</p>

<pre><code>![alt text](/path/img.jpg "Title")
</code></pre>

<p>Reference-style:</p>

<pre><code>![alt text][id]

[id]: /url/to/img.jpg "Title"
</code></pre>

<h3>Headers</h3>

<p>Setext-style:</p>

<pre><code>Header 1
========

Header 2
--------
</code></pre>

<p>atx-style (closing #'s are optional):</p>

<pre><code># Header 1 #

## Header 2 ##

###### Header 6
</code></pre>

<h3>Lists</h3>

<p>Ordered, without paragraphs:</p>

<pre><code>1.  Foo
2.  Bar
</code></pre>

<p>Unordered, with paragraphs:</p>

<pre><code>*   A list item.

    With multiple paragraphs.

*   Bar
</code></pre>

<p>You can nest them:</p>

<pre><code>*   Abacus
    * answer
*   Bubbles
    1.  bunk
    2.  bupkis
        * BELITTLER
    3. burper
*   Cunning
</code></pre>

<h3>Blockquotes</h3>

<pre><code>&gt; Email-style angle brackets
&gt; are used for blockquotes.

&gt; &gt; And, they can be nested.

&gt; #### Headers in blockquotes
&gt; 
&gt; * You can quote a list.
&gt; * Etc.
</code></pre>

<h3>Code Spans</h3>

<pre><code>`&lt;code&gt;` spans are delimited
by backticks.

You can include literal backticks
like `` `this` ``.
</code></pre>

<h3>Preformatted Code Blocks</h3>

<p>Indent every line of a code block by at least 4 spaces or 1 tab.</p>

<pre><code>This is a normal paragraph.

    This is a preformatted
    code block.
</code></pre>

<h3>Horizontal Rules</h3>

<p>Three or more dashes or asterisks:</p>

<pre><code>---

* * *

- - - - 
</code></pre>

<h3>Manual Line Breaks</h3>

<p>End a line with two or more spaces:</p>

<pre><code>Roses are red,   
Violets are blue.
</code></pre>
</div> <!-- sidebar -->
</div>

<footer>
  <p>Предложения, комментарии — в твиттер <a href="https://twitter.com/kost">@kost</a> или на <a href="https://github.com/akost/marktypo">GitHub</a></p>
  <h4>По мотивам:</h4>
    <ul>
    <li><a href="http://daringfireball.net/projects/markdown/dingus">http://daringfireball.net/projects/markdown/dingus</a></li>
    <li><a href="http://www.artlebedev.ru/tools/typograf/">http://www.artlebedev.ru/tools/typograf/</a></li>
  </ul>
</footer>
</body>
</html>