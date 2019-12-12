<?php

// ini_set('log_errors','on');
// ini_set('error_log','php.log');
session_start();

$great = array();// 偉人の変数に、配列を代入
// クラス定数にて、偉人が言うセリフのクラスを作成。public function saySerif()にて、該当の偉人のセリフがランダムに出力されるように使用
class Serif{
  const LINCOLN = 1;
  const MOUNTRUSHMORE = 2;
  const PYTHAGORAS = 3;
  const ARCHIMEDES = 4;
  const ONONOKOMACHI = 5;
  const GALILEO = 6;
  const NIGHTINGALE = 7;
  const TAKIRENNTAROU = 8;
}
// プレイヤーの「学ぶ者」と、画面に出現する「偉人」の元となる抽象クラス「人クラス」
abstract class Human{
  protected $name;
  protected $concentration;
  protected $studyMin;
  protected $studyMax;
  abstract public function saySerif(); // 「学ぶ者」と「偉人」でセリフが異なる。抽象メソッドを使用
  // キャラクターの名前と、キャラクターの持つ集中力のセッターとゲッター
  public function setName($str){
    $this->name = $str;
  }
  public function getName(){
    return $this->name;
  }
  public function setConcentration($num){
    $this->concentration = filter_var($num, FILTER_VALIDATE_INT); // 小数ではなく、整数で入るようにチェック
  }
  public function getConcentration(){
    return $this->concentration;
  }
  // 「学ぶ者」も「偉人」も、相手の集中力を奪う動作は同じなので、保守性のためpublic function study($targetObj)としてまとめている
  public function study($targetObj){
    $studyPoint = mt_rand($this->studyMin, $this->studyMax);
    if(!mt_rand(0,9)){ //10分の1の確率でヒラメキ（攻撃力1.5倍）
      $studyPoint = $studyPoint * 1.5;
      $studyPoint = (int)$studyPoint; // 1.5倍した数が小数の場合があるので、int型にキャストし整数にする
      History::set('なんと！'.$this->getName().'の天才的なヒラメキ！！（攻撃力1.5倍）');
    }
    $targetObj->setConcentration($targetObj->getConcentration()-$studyPoint); // 引数に入る情報によって、相手を変えられるように
    History::set($studyPoint.'ポイントのダメージ！');
  }
}
// 学ぶ者クラス
class Student extends Human{
  public function __construct($name, $concentration, $studyMin, $studyMax) { // プロパティの設定・初期化
    $this->name = $name;
    $this->concentration = $concentration;
    $this->studyMin = $studyMin;
    $this->studyMax = $studyMax;
  }
  public function saySerif(){
    History::set('「いたッッッッ、これは効いたっ・・！」');
  }
}
// 偉人クラス
class Great extends Human{
  protected $img; // 「偉人クラス」のみ画像を使うので、プロパティを追加
  public function __construct($name, $serif, $concentration, $img, $studyMin, $studyMax) { // プロパティの設定・初期化
    $this->name = $name;
    $this->serif = $serif;
    $this->concentration = $concentration;
    $this->Maxconcentration = $concentration;
    $this->img = $img;
    $this->studyMin = $studyMin;
    $this->studyMax = $studyMax;
  }
//  public function setSerif($num){  不要・・・
//    $this->serif = $num;
//  }
//  public function getSerif(){
//    return $this->serif;
//  }

  public function saySerif(){ //各偉人に応じて、セリフをランダムに生成
    switch($this->serif){
      case Serif::LINCOLN :
        $serif_rand = array(
          '「1861年って想像できるか？<br>今から150年前だ。<br><br>16代のアメリカ合衆国大統領に就任したのはこのオレ。」',
          '「おッ！まあまあできるようだな。<br>油断するなよ！」',
          '奴隷解放宣言！',
          '民主主義？オレが当たり前にしたんだよ！<br><br>「人民の、人民による、人民のための政治」よぉ。',
          '（鼻歌）USA♪USA♪',
        );
        $serif_rand = $serif_rand[mt_rand(0, count($serif_rand)-1)];
        return $serif_rand;
        break;
      case Serif::MOUNTRUSHMORE :
        $serif_rand = array(
          'ラシュモア山<br>国立記念公園・・・',
          '14年間・・像・・彫られた・・。1927年〜1941年。<br><br>・・高さ・・60フィート（18m）。。',
          '一番右が・・、<br><br>エイブラハム・リンカーン・・・',
          '彫られているのは・・・・<br><br><br>アメリカ大統領４人・・',
          '・・・ダイナマイトで・・砕いて作っていった。。<br><br>１６億年前の花崗岩・・めちゃ・・めちゃ・・硬い',
        );
        $serif_rand = $serif_rand[mt_rand(0, count($serif_rand)-1)];
        return $serif_rand;
        break;
      case Serif::PYTHAGORAS :
      $serif_rand = array(
          'ピタゴラーーー<br>スイッッッチーーーー！！！',
          '神秘！！数の法則ーー！<br><br>宇宙の全ては、<br>数で成り立つーー！',
          'おれっち、豆がニガテー！<br>みんなも食べちゃダメーー！<br>見た目がダメだね！',
          'ピタゴラスの定理<br><br>（三平方の定理）！！<br><br>イエーーーーイ！！',
          'ちょちょちょ直角三角形！<br>しゃしゃしゃ斜辺の2乗は！？<br>SAY！！<br>「直角をはさむ辺を、2乗して足したものと等しい」！！',
        );
        $serif_rand = $serif_rand[mt_rand(0, count($serif_rand)-1)];
        return $serif_rand;
        break;
      case Serif::ARCHIMEDES :
      $serif_rand = array(
        '「体重50kgの人は、水に浮くやん？<br>でも、50kgの鉄球は沈む。<br><br>密度が、浮力で支えきれるかどうかね」',
        '数学得意〜。<br>科学・発明・武器の開発なんでもOKよ〜',
        '「宇宙は無限の広がり？？<br><br>いやいや、砂つぶの数で表せられると思うのよね〜」',
        '「紀元前200年代に生きたけど、75才頃まで生きたって。<br><br>なかなか、長寿じゃない？」',
        '「へウレーカ！ヘウレーカ！<br>（わかった！ わかったぞ！）」',
      );
      $serif_rand = $serif_rand[mt_rand(0, count($serif_rand)-1)];
      return $serif_rand;
        break;
      case Serif::ONONOKOMACHI :
      $serif_rand = array(
        '花の色は<br>移りにけりな<br>いたづらに<br><br>我が身世にふる<br>ながめせし間に',
        '「咲き誇った桜の花も、<br>むなしく色あせてしまったわ・・<br><br>ちやほやされていたのに、、<br>老け込んでしまって・・」',
        '「秋田県湯沢市小野出身<br>と言われているわ」',
        '恋に生きて、歌を詠んで。',
        '「六歌仙や、三十六歌仙になんて選ばれているけどねぇ」',
      );
      $serif_rand = $serif_rand[mt_rand(0, count($serif_rand)-1)];
      return $serif_rand;
        break;
      case Serif::GALILEO :
      $serif_rand = array(
        '「月にデコボコがあるんだな・・<br>みんなは月はツルツルって言ってるけど、本当は違うんだな」',
        '「レールの上を、転がる球は、<br>重さが違っても、<br>落ちる速さは同じなんだな・・」',
        '「木星に衛星が・・あるんだな・・」。<br><br>のちにガリレオ衛星と呼ばれるのである',
        '科学の父。<br>自分で実験を行い、自分の目で確かめる',
        '「やっぱり、地球が太陽の周りを回っているんだな」',
      );
      $serif_rand = $serif_rand[mt_rand(0, count($serif_rand)-1)];
      return $serif_rand;
        break;
      case Serif::NIGHTINGALE :
      $serif_rand = array(
        '「何でも学んだわ。<br>フランス語・ギリシャ語・イタリア語・ラテン語・<br>哲学・数学・天文学・経済学・<br>地理・心理学・詩・美術・音楽」',
        '「人に何と言われようと、<br>私は看護士を志したの」',
        '「統計学役に立ったわ。<br>戦死者が多いのは、戦ったからじゃないわ<br><br>不衛生だったからよ」',
        '「あなた、なかなかやるじゃない？」',
        '「お金が必要よ<br>真の奉仕は、犠牲なき貢献。<br><br>・・ボランティアは長く続かないわ」',
      );
      $serif_rand = $serif_rand[mt_rand(0, count($serif_rand)-1)];
      return $serif_rand;
        break;
      case Serif::TAKIRENNTAROU :
      $serif_rand = array(
        '「荒城の月」・「箱根八里」',
        '「おお！センスあるんじゃない？」',
        '結核についての偏見のため、<br>死後多数の作品が燃やされた・・',
        '日本人作曲家による、<br>初のピアノ独奏曲「メヌエット」',
        '「肺結核・・23年間の人生だったけど、楽しかったなあ」',
      );
      $serif_rand = $serif_rand[mt_rand(0, count($serif_rand)-1)];
      return $serif_rand;
        break;
    }
  }

  public function getMaxconcentration(){
    return $this->Maxconcentration;
  }
  public function getImg(){
    return $this->img;
  }
}
// パワーアップした、特別な偉人「本気を出した偉人クラス（アルキメデスと、ラッシュモアのみ）」
class SeriousGreat extends Great{
  private $seriousStudy; // これ以上継承しないので、アクセス権はprivate
  function __construct($name, $serif, $concentration, $img, $studyMin, $studyMax, $seriousStudy) {
    parent::__construct($name, $serif, $concentration, $img, $studyMin, $studyMax);
    $this->seriousStudy = $seriousStudy;
  }
  public function getSeriousStudy(){
    return $this->seriousStudy;
  }
  public function study($targetObj){
    if(!mt_rand(0,4)){ //5分の1の確率で、本気の偉人の課題
      History::set($this->name.'の本気の課題！！（攻撃力1.5倍）');
      $targetObj->setConcentration( $targetObj->getConcentration() - $this->seriousStudy );
      History::set($this->seriousStudy.'ポイントのダメージ！');
    }else{
      parent::study($targetObj);
    }
  }
}
interface HistoryInterface{
  public static function set($str);
  public static function clear();
}
// 履歴管理クラス（１つしか使わないので、static）
class History implements HistoryInterface{
  public static function set($str){
    // セッションhistoryが作られてなければ作成
    if(empty($_SESSION['history'])) $_SESSION['history'] = '';
    // 文字列をセッションhistoryへ格納
    $_SESSION['history'] .= $str.'<br>';
  }
  public static function clear(){
    unset($_SESSION['history']);
  }
}

// インスタンス生成
$student = new Student('学ぶ者', 500, 40, 120);
$greats[] = new Great( '【英】Lincoln', Serif::LINCOLN, 100, 'img/lincoln.png', 20, 40 );
$greats[] = new SeriousGreat( '【英】<br>Mount Rushmore<br>【４人の大統領】', Serif::MOUNTRUSHMORE, 300, 'img/landmark_mount_rushmore.png', 20, 60, mt_rand(50, 100) );
$greats[] = new Great( '【数】ピタゴラス', Serif::PYTHAGORAS, 200, 'img/pythagoras.png', 30, 50 );
$greats[] = new SeriousGreat( '【数】<br>アルキメデス<br>【第一級の科学者】', Serif::ARCHIMEDES, 400, 'img/archimedes.png', 50, 80, mt_rand(60, 120) );
$greats[] = new Great( '【国】紫式部', Serif::ONONOKOMACHI, 150, 'img/onono_komachi.png', 30, 60 );
$greats[] = new Great( '【理】ガリレオ・ガリレイ', Serif::GALILEO, 100, 'img/galileo.png', 10, 30 );
$greats[] = new Great( '【社】ナイチン・ゲール', Serif::NIGHTINGALE, 120, 'img/nightingale.png', 20, 30 );
$greats[] = new Great( '【音楽】滝廉太郎', Serif::TAKIRENNTAROU, 180, 'img/taki_rentarou.png', 30, 50 );

function createGreat(){ // 偉人作成関数
  global $greats;
  $great =  $greats[mt_rand(0, 7)];
  History::set('>>> 偉人が現れた！<br>'.$great->getName().'が何かを伝えようとしている！');
  $_SESSION['great'] =  $great;
}
function createStudent(){ // 学ぶ者作成関数
  global $student;
  $_SESSION['student'] = $student;
}
function init(){ // ゲーム初期化関数
  History::clear();
  History::set(' >>>>>>>>>> はじめ！<<<<<<<<<< <br>');
  $_SESSION['understandingCount'] = 0;
  createStudent();
  createGreat();
}
function gameOver(){
  $_SESSION = array();
}

//POST送信されていた場合
if(!empty($_POST)){
  $restartFlg = (!empty($_POST['restart'])) ? true : false;
  error_log('result.phpにて、POST送信されました。');

  if($restartFlg){
    gameOver();
    $_POST = array();
    header("Location:index.php");
    exit;
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta property="og:url" content="https://ijinden.idoharu.com">
  <meta property="og:title" content="偉人伝">
  <meta property="og:description" content="歴史上の偉人から学び取って、倒しまくろう！">
  <meta property="og:image" content="img/greats.png">
  <title>偉人伝</title>
  <link href="https://fonts.googleapis.com/css?family=Kosugi+Maru|Paytone+One&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="shortcut icon" href="img/lincoln.png" type="image/png">
</head>
<body>
  <section class="result">
    <div class="result__wrapper">
      <h2 class="result__wrapper--title">完</h2>
        <div class="result__wrapper--message">学びを終えた・・</div>
        <div class="result__wrapper--victory">
          <img src="img/icon_victory.png"><br><span>学び倒した<br>偉人の数：<?php echo $_SESSION['understandingCount']; ?></span>人
        </div>
        <div class="result__flex">
          <div class="result__restart">
            <form method="post">
              <input class="post--rst" type="submit" name="restart" value="▶ゲームリスタート">
            </form>
          </div>
        </div>
    </div>
  </section>
  <footer class="footer">
    <p>Copyright © <a href="https://idoharu.com">idoharu.com</a>. All Rights Reserved</p>
  </footer>
</body>
</html>
