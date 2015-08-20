#! /usr/bin/perl

use strict;

my @php_html_files = <*.php.html>;

my $header = <<'END1';
<?php
	include('doctype.php');
?>

<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Torc: Tools for Open Reconfigurable Computing</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <link href="default.css" rel="stylesheet" type="text/css">
  <style type="text/css">
<!--
	@import url("layout.css");
-->
  </style>
</head>
<body>

<?php
	include('header.php');
?>

END1
my $footer = <<'END2';

<?php
	include('footer.php');
?>

</body>
</html>
END2

foreach my $php_html_file (@php_html_files) {
    #next if $php_html_file =~ /(doctype.php|header.php|footer.php)/;
    print "Reading $php_html_file\n";
    open(PHP, $php_html_file) or die "Unable to read $php_html_file: $!\n";
    my $lines = join "", <PHP>;
    close PHP;
    next unless $lines =~ /^(.*<body>)(\s*<(?:!--)?\?php\s+include\('header.php'\);\s+\?(?:--)?>)?\s*(    .*?)\s*(<(?:!--)?\?php\s+include\('footer.php'\);\s+\?(?:--)?>\s*)?(<\/body>\s*<\/html>\s*)/s;
    my ($preamble, $php_header, $content, $php_footer, $postamble) = ($1, $2, $3, $4, $4);
    print "\tFound php block\n";
	my $php_file = $php_html_file;
	$php_file =~ s/\.html$//;
    `cp $php_file $php_file.bak`;
	print "\tWriting $php_file\n";
    open(PHP, ">$php_file") or die "Unable to write $php_file: $!\n";
    print PHP "$header$content\n$footer";
    close PHP;
}

