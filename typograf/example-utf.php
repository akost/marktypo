<html>
<head>
  <title>ArtLebedevStudio.RemoteTypograf example</title>
  <style>
  body {
    margin: 20px 10px;
  }
    nobr
    {
      background-color: #EEF1E5;
    }
  </style>
</head>
<body>
  <?
    $text = stripslashes ($_POST[text]);
    if (!$text) $text = '"Вы все еще кое-как верстаете в "Ворде"? - Тогда мы идем к вам!"';
  ?>

  <form method="post">
    <textarea style="width: 99%; height: 300px" name="text"><? echo $text; ?></textarea>
    <p>
      <input type="submit" value="ProcessText" />
    </p>   
  </form> 
    <div style="width:45%;float:left;">
    <?
      if ($_POST[text])
      {
        include "remotetypograf.php";
        
        $remoteTypograf = new RemoteTypograf();

        $remoteTypograf->noEntities();
        $remoteTypograf->br (false);
        $remoteTypograf->p (false);
        $remoteTypograf->nobr (3);
        $remoteTypograf->quotA ('laquo raquo');
        $remoteTypograf->quotB ('bdquo ldquo');

        $result =  $remoteTypograf->processText ($text);
      }
    ?>
    <p><label for="result">Результат</label></p>
    <textarea style="width: 99%; height: 300px" name="result" id="result"><? echo $result; ?></textarea>
    </div>
    <div style="width:45%;float:right;">

    <p><label for="result">Результат</label></p>
    <textarea style="width: 99%; height: 300px" name="result" id="result"><? echo $result; ?></textarea>
    </div>
  <div style="clear:both"></div>
</body>
</html>

