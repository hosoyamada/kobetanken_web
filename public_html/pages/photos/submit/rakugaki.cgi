#!/usr/local/bin/perl

require 'jcode.pl';

$rakugakiurl = 'http://www.tryhp.net/cgi-bin/rakugaki.cgi';
$rakugakifile = 'rakugaki.txt';
($sec,$min,$hour,$day,$mon,$year,$wday,$yday,$isdst) = localtime(time);
$year += 1900;
$mon = sprintf("%02d", $mon + 1);
$day = sprintf("%02d", $day);
$hour = sprintf("%02d", $hour);
$min = sprintf("%02d", $min);
$date_now = "$year年$mon月$day日 $hour時$min分";

if ($ENV{'REQUEST_METHOD'} eq "POST") {
  read(STDIN, $QUERY_DATA, $ENV{'CONTENT_LENGTH'});
} else { $formdata = $ENV{'QUERY_STRING'}; }

@pairs = split(/&/,$QUERY_DATA);
foreach $pair (@pairs) {
  ($name, $value) = split(/=/, $pair);
  $value =~ tr/+/ /;
  $value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
  $value =~ s/</&lt;/g;
  $value =~ s/>/&gt;/g;
  $value =~ s/\n//g;
  $value =~ s/\,//g;
  &jcode'convert(*value,'sjis');
  $FORM{$name} = $value;
}

if ($FORM{'action'} eq "true") { &regist; }
else { &html; }
exit;

sub html {
  if (!open(NOTE,"$rakugakifile")) { &error(bad_file); }
  @DATA = <NOTE>;
  close(NOTE);
  @DATA = reverse(@DATA);
  print "Content-type: text/html\n\n";
  print "<!DOCTYPE HTML PUBLIC -//IETF//DTD HTML//EN>\n";
  print "<html>\n";
  print "<head>\n";
  print "<meta http-equiv=Content-Type content= text/html; charset=x-sjis>\n";
  print "<title>落書き帳</title></head>\n";
  print "<body bgcolor=#000000 text=#FFFFFF>\n";
  print "<form action=rakugaki.cgi method=POST>\n";
  print "<input type=hidden name=action value=true>\n";
  print "<div align=center><center>\n";
  print "<table border=1 cellspacing=1>\n";
  print "<tr>\n";
  print "<td align=center>ニックネーム</td>\n";
  print "<td><input type=text size=29 name=name></td>\n";
  print "</tr>\n";
  print "<tr>\n";
  print "<td align=center>E-mail</td>\n";
  print "<td><input type=text size=29 name=email></td>\n";
  print "</tr>\n";
  print "<tr>\n";
  print "<td align=center>言いたい</td>\n";
  print "<td><textarea name=comment rows=4 cols=68></textarea></td>\n";
  print "</tr>\n";
  print "<tr>\n";
  print "<td align=center colspan=2><input type=submit value=書いちゃえ></td>\n";
  print "</tr>\n";
  print "</table></center></div>\n";
  print "</form>\n";
  print "<div align=center><center>\n";
  foreach $line (@DATA) {
    chop($line);
    ($date,$name,$email,$comment) = split(/\,/,$line);
    $comment =~ s/\r/<br>/g;
    print "<table border=0 width=80% bgcolor=#FFFFFF>\n";
    print "<tr>\n";
    print "<td><font color=#000000>\n";
    if ($email ne "") {
      print "<a href=mailto:$email><strong>$name</strong></a>\n";
    } else { print "<strong>$name</strong>\n"; }
    print "  $date<br>\n";
    print "<blockquote>$comment</blockquote>\n";
    print "</font>\n";
    print "</td>\n";
    print "</tr>\n";
    print "</table>\n";
    print "<p>";
  }
  print "</center></div>\n";
  print "</body></html>\n";
  exit;
}

sub regist {
  if ($FORM{'name'} eq "") { &error(bad_name); }
  if ($FORM{'comment'} eq "") { &error(bad_comment); }
  if (!open(NOTE,">>$rakugakifile")) { &error(bad_file); }
  $value = "$date_now\,$FORM{'name'}\,$FORM{'email'}\,$FORM{'comment'}\n";
  print NOTE $value;
  close(NOTE);
  print "Location: $rakugakiurl" . '?' . "\n\n";
}

sub error {
  $error = $_[0];
  if ($error eq "bad_file") { $msg = 'ファイルのオープン、入出力に失敗しました。'; }
  elsif ($error eq "bad_name") { $msg = 'ニックネームが記入されていません。'; }
  elsif ($error eq "bad_comment") { $msg = 'コメントが記入されていません。'; }
  else { $msg = '原因不明のエラーで処理を継続できません。'; }
  print "Content-type: text/html\n\n";
  print "<html><head><title>落書き帳</title></head>\n";
  print "<body bgcolor=#000000 text=#FFFFFF LINK=#FFAAAA VLINK=#FF8888>\n";
  print "<p>\n";
  print "<center><h2>error</h2><hr>\n";
  print "<i>" . $msg . "</i></hr></center>\n";
  print "</body></html>\n";
  exit;
}
