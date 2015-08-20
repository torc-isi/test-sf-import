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

    <table align="center" border="0" cellpadding="0" cellspacing="0"
      width="650">
      <tbody>
        <tr>
          <td width="415"><img src="images/spacer.png" alt=""
              height="30" width="30"></td>
        </tr>
        <tr valign="top">
          <td>
            <table style="width: 100%;" border="0" cellpadding="0"
              cellspacing="0">
              <tbody>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td class="text4"><img src="images/img4.png" alt=""
                      height="1" width="100%">Examples</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>
                    <div style="text-align: left;"> </div>
                    Refer to <a class="link2"
                      href="getting_started.php">Getting Started</a> for
                    information on how to build the following examples:<br>
                    <br>
                    <ul>
                      <li><a class="link2"
                          href="#ArchitectureExample.cpp">torc/examples/ArchitectureExample.cpp</a></li>
                      <li><a class="link2" href="#BitstreamExample.cpp">torc/examples/BitstreamExample.cpp</a></li>
                      <li><a class="link2" href="#GenericExample.cpp">torc/examples/GenericExample.cpp</a></li>
                      <li><a class="link2" href="#PhysicalExample.cpp">torc/examples/PhysicalExample.cpp</a></li>
                      <li><a class="link2" href="#EdifObfuscator.cpp">torc/examples/EdifObfuscator.cpp</a></li>
                    </ul>
                    <br>
                    <br>
                    <div style="margin-left: 40px;"> </div>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;"><br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;" class="text4"><a
                      name="ArchitectureExample.cpp"></a><img
                      src="images/img4.png" alt="" height="1"
                      width="100%">torc/examples/ArchitectureExample.cpp<br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;"><br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">The architecture API
                    example demonstrates basic use of the device
                    database.&nbsp; It begins by initializing a
                    DirectoryTree object with the invocation path of the
                    executable.&nbsp; The location of the executable
                    will serve as the reference point for all other
                    directories, and in particular for the "devices"
                    directory with all of its device databases.<br>
                    <br>
                    A DeviceDesignator object is initialized for
                    convenience with a particular Virtex6 device,
                    package, and speed grade (the package and speed
                    grade being optional), which it parses.&nbsp;
                    Functions in the DeviceDesignator class allow access
                    to the family, device, package, and speed grade.<br>
                    <br>
                    The device database object is then constructed,
                    using the designator object to determine which
                    device and package to load.&nbsp; The appropriate
                    database file is read from disk, and is used to
                    populate all wiring, logic, and package information
                    for the device.<br>
                    <br>
                    Once the device database is initialized, the code
                    looks up a particular logic site, and then looks up
                    a particular site pin.&nbsp; That pin is returned as
                    a "tilewire," a numeric combination of a wire index
                    and a tile index, and is written to the
                    output.&nbsp; The arcs that can connect this wire to
                    other wires are output as well.<br>
                    <br>
                    <pre style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; color: rgb(0, 0, 0); -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous;"><span style="color: rgb(63, 127, 89);"></span><br><span style="color:#7f0055; ">#</span><span style="color:#7f0055; ">include </span><span style="color:#2a00ff; ">"</span><span style="color:#3f3fbf; ">torc/Architecture.hpp</span><span style="color:#2a00ff; ">"</span>
<span style="color:#7f0055; ">#</span><span style="color:#7f0055; ">include </span><span style="color:#2a00ff; ">"</span><span style="color:#3f3fbf; ">torc/Common.hpp</span><span style="color:#2a00ff; ">"</span>
<span style="color:#7f0055; ">#</span><span style="color:#7f0055; ">include </span><span style="color:#2a00ff; ">&lt;</span><span style="color:#3f3fbf; ">iostream</span><span style="color:#2a00ff; ">&gt;</span>

<span style="color:#7f0055; font-weight:bold; ">using</span> <span style="color:#7f0055; font-weight:bold; ">namespace</span> torc::common;
<span style="color:#7f0055; font-weight:bold; ">using</span> <span style="color:#7f0055; font-weight:bold; ">namespace</span> torc::architecture;
<span style="color:#7f0055; font-weight:bold; ">using</span> <span style="color:#7f0055; font-weight:bold; ">namespace</span> torc::architecture::xilinx;

<span style="color:#7f0055; font-weight:bold; ">int</span> <span style="color:#7f0055; font-weight:bold; ">main</span>(<span style="color:#7f0055; font-weight:bold; ">int</span> argc, <span style="color:#7f0055; font-weight:bold; ">char</span>* argv[]) {

    <span style="color:#3f7f59; ">// construct and initialize the device database</span>
    (<span style="color:#7f0055; font-weight:bold; ">void</span>) argc;
    DirectoryTree directoryTree(argv[0]);
    DeviceDesignator designator(<span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">xc6vlx75tff484-1</span><span style="color:#2a00ff; ">"</span>);
    DDB ddb(designator);

    <span style="color:#3f7f59; ">// look up a site output and convert it to a tilewire</span>
    <span style="color:#7f0055; font-weight:bold; ">const</span> Sites&amp; sites = ddb.getSites();
    SiteIndex index = sites.findSiteIndex(<span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">SLICE_X0Y119</span><span style="color:#2a00ff; ">"</span>);
    <span style="color:#7f0055; font-weight:bold; ">const</span> Site&amp; site = sites.getSite(index);
    Tilewire pinTilewire = site.getPinTilewire(<span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">A</span><span style="color:#2a00ff; ">"</span>);
    std::<span style="color:#7f0055; font-weight:bold; ">cout</span> &lt;&lt; ddb &lt;&lt; pinTilewire &lt;&lt; std::endl;

    <span style="color:#3f7f59; ">// look up arcs that connect from this tilewire</span>
    ArcVector sinks;
    ddb.expandSegmentSinks(pinTilewire, sinks);
    ArcVector::<span style="color:#7f0055; font-weight:bold; ">const_iterator</span> pos = sinks.begin();
    ArcVector::<span style="color:#7f0055; font-weight:bold; ">const_iterator</span> end = sinks.end();
    <span style="color:#7f0055; font-weight:bold; ">while</span>(pos &lt; end) 
        std::<span style="color:#7f0055; font-weight:bold; ">cout</span> &lt;&lt; <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">\t</span><span style="color:#2a00ff; ">"</span> &lt;&lt; *pos++ &lt;&lt; std::endl;

    <span style="color:#7f0055; font-weight:bold; ">return</span> 0;
}
</pre>
                    <br>
                    The tilewire is a lightweight encapsulation of an
                    integer that lacks any knowledge of the current
                    database.&nbsp; Its annotated output is made
                    possible because the code first inserted the
                    database object ddb into the output stream
                    ("std::cout &lt;&lt; ddb").&nbsp; This insertion
                    only needs to be done one time per stream.&nbsp;
                    Without this insertion, the tilewire output would
                    simply be the combination of its tile and wire
                    indexes, for example "189@186".<br>
                    <br>
                    <pre style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; color: rgb(0, 0, 0); -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous;">WARNING: Need to check segment packing.
Reading device xc6vlx75t (<span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">./torc/devices/xc6vlx75t.db</span><span style="color:#2a00ff; ">"</span>)...
        Database 1.0.0 build 2, Vendor <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">Release 12.2 - xdl M.63c (lin64)</span><span style="color:#2a00ff; ">"</span>
        Reading family Virtex6 (<span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">./torc/devices/Virtex6.db</span><span style="color:#2a00ff; ">"</span>)...
        Reading 96 tile types...
        Reading wire info for 96 tile types...
        Reading 3 speed grades (-3, -2, -1) ... 
        Reading 2 packages (ff484, ff784) ...
        Reading tile map for 22733 tiles (127 rows x 179 columns)...
        Reading 22733 tiles...
        Reading 38074 segments...
        1673499 total segments
        Reading irregular arcs for 22733 tiles...
        Reading 51 site types...
        Reading 210 primitive pin maps...
        Reading 22509 sites...
Read 2566212 bytes from Virtex6
Read 14450208 bytes from xc6vlx75t
CLBLM_M_A@[1,7] CLBLM <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">CLBLM_X1Y119</span><span style="color:#2a00ff; ">"</span> (189@186) OUTPUT
        CLBLM_M_A@[1,7] CLBLM <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">CLBLM_X1Y119</span><span style="color:#2a00ff; ">"</span> (189@186) OUTPUT &gt;&gt; CLBLM_LOGIC_OUTS12@[1,7] CLBLM <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">CLBLM_X1Y119</span><span style="color:#2a00ff; ">"</span> (121@186)
        CLBLM_M_A@[1,7] CLBLM <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">CLBLM_X1Y119</span><span style="color:#2a00ff; ">"</span> (189@186) OUTPUT &gt;&gt; CLBLM_M_AMUX@[1,7] CLBLM <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">CLBLM_X1Y119</span><span style="color:#2a00ff; ">"</span> (197@186) OUTPUT
</pre>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;"><br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;" class="text4"><a
                      name="BitstreamExample.cpp"></a><img
                      src="images/img4.png" alt="" height="1"
                      width="100%">torc/examples/BitstreamExample.cpp<br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;"><br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">The bitstream API
                    example reads a bitstream file specified on the
                    command line, determined the correct architecture,
                    reads and parses its packets into a new Bitstream
                    object (which is itself a vector of packets), and
                    writes those packets to the standard output.<br>
                    <br>
                    <pre style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; color: rgb(0, 0, 0); -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous;"><span style="color:#7f0055; ">#</span><span style="color:#7f0055; ">include </span><span style="color:#2a00ff; ">"</span><span style="color:#3f3fbf; ">torc/Bitstream.hpp</span><span style="color:#2a00ff; ">"</span>
<span style="color:#7f0055; ">#</span><span style="color:#7f0055; ">include </span><span style="color:#2a00ff; ">"</span><span style="color:#3f3fbf; ">torc/Common.hpp</span><span style="color:#2a00ff; ">"</span>
<span style="color:#7f0055; ">#</span><span style="color:#7f0055; ">include </span><span style="color:#2a00ff; ">&lt;</span><span style="color:#3f3fbf; ">fstream</span><span style="color:#2a00ff; ">&gt;</span>
<span style="color:#7f0055; ">#</span><span style="color:#7f0055; ">include </span><span style="color:#2a00ff; ">&lt;</span><span style="color:#3f3fbf; ">iostream</span><span style="color:#2a00ff; ">&gt;</span>

<span style="color:#7f0055; font-weight:bold; ">using</span> <span style="color:#7f0055; font-weight:bold; ">namespace</span> torc::common;
<span style="color:#7f0055; font-weight:bold; ">using</span> <span style="color:#7f0055; font-weight:bold; ">namespace</span> torc::bitstream;

<span style="color:#7f0055; font-weight:bold; ">int</span> <span style="color:#7f0055; font-weight:bold; ">main</span>(<span style="color:#7f0055; font-weight:bold; ">int</span> argc, <span style="color:#7f0055; font-weight:bold; ">char</span>* argv[]) {

    <span style="color:#3f7f59; ">// we need an input bitstream</span>
    <span style="color:#7f0055; font-weight:bold; ">if</span>(argc != 2) {
        std::<span style="color:#7f0055; font-weight:bold; ">cout</span> &lt;&lt; <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">Usage: </span><span style="color:#2a00ff; ">"</span> &lt;&lt; argv[0] &lt;&lt; <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; "> bitstream.bit</span><span style="color:#2a00ff; ">"</span> &lt;&lt; std::endl;
        <span style="color:#7f0055; font-weight:bold; ">exit</span>(-1);
    }

    <span style="color:#3f7f59; ">// read the bitstream</span>
    boost::filesystem::path bitstreamPath = argv[1];
    BitstreamSharedPtr bitstreamPtr = Factory::newBitstreamPtr(bitstreamPath);
    <span style="color:#3f7f59; ">// write the bitstream digest to the console</span>
    std::<span style="color:#7f0055; font-weight:bold; ">cout</span> &lt;&lt; *bitstreamPtr &lt;&lt; std::endl;

    <span style="color:#7f0055; font-weight:bold; ">return</span> 0;
}
</pre>
                    <br>
                    After the bitstream has been read and parsed, the
                    bitstream object is "inserted" into the standard
                    output.&nbsp; An output helper class formats the
                    output as a sequence of bitstream packets, complete
                    with symbolic bit field constants.<br>
                    <br>
                    <pre style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; color: rgb(0, 0, 0); -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous;">Design VirtexUnitTest.reference.ncd (v50bg256) @ 2011/01/26 11:51:59: 69900 bytes (17475 words)
    00000058: DUMMY
    0000005c: SYNC
    00000060: TYPE1 WRITE CMD RCRC
    00000068: TYPE1 WRITE FLR: 0000000b
    00000070: TYPE1 WRITE COR: 00803f2d (DonePipe:No, DriveDone:No, Capture:Continuous, ConfigRate:[UNKNOWN 2], <br>StartupClk:Cclk, LOCK_WAIT:1, SHUTDOWN:0, DONE_cycle:4, LCK_cycle:NoWait, GTS_cycle:5, GWE_cycle:6, GSR_cycle:6)
    00000078: TYPE1 WRITE MASK: 00000000 (Security:Protected, Persist:Protected, GTS_USER_B:Protected)
    00000080: TYPE1 WRITE CMD SWITCH
    00000088: TYPE1 WRITE FAR: 00000000
    00000090: TYPE1 WRITE CMD WCFG
    00000098: TYPE1 WRITE FDRI: 00000000 words
    0000009c: TYPE2 WRITE FDRI: 00003e04 words
    0000f8b0: TYPE1 WRITE FAR: 02000000
    0000f8b8: TYPE1 WRITE FDRI: 0000030c words
    000104ec: TYPE1 WRITE FAR: 02020000
    000104f4: TYPE1 WRITE FDRI: 00000300 words
    000110f8: TYPE1 WRITE CRC: 000018fa
    00011100: TYPE1 WRITE CMD LFRM
    00011108: TYPE1 WRITE FDRI: 0000000c words
    0001113c: TYPE1 WRITE CMD START
    00011144: TYPE1 WRITE CTL: 00000000 (Security:None, Persist:No, GTS_USER_B:IoDisabled)
    0001114c: TYPE1 WRITE CRC: 0000e15a
    00011154: [UNKNOWN TYPE 0]
    00011158: [UNKNOWN TYPE 0]
    0001115c: [UNKNOWN TYPE 0]
    00011160: [UNKNOWN TYPE 0]
</pre>
                    <br>
                    Bitfield constants can also be initialized
                    symbolically for convenience, as is done in
                    torc/bitstream/VirtexBitstreamUnitTest.cpp.<br>
                    <br>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;"><br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;" class="text4"><a
                      name="GenericExample.cpp"></a><img
                      src="images/img4.png" alt="" height="1"
                      width="100%">torc/examples/GenericExample.cpp<br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;"><br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">The generic API
                    example reads an EDIF file, updates a property, and
                    writes the file back out.&nbsp; Like the other
                    examples, this one too begins by initializing the
                    DirectoryTree.&nbsp; The code then specifies the two
                    files it will work with, the first one already
                    existing in the regression directory, and the second
                    one to be used to output the modified design.<br>
                    <br>
                    The code opens the reference file and imports the
                    EDIF design into its object model.&nbsp; It then
                    looks up a particular library, cell, and view, to
                    find an instance of interest.&nbsp; The INIT
                    property for that instance is changed from its
                    previous value of "8" to "6", effectively turning
                    this 2-input LUT from an AND gate into an XOR
                    gate.&nbsp; The code then opens the output file and
                    lets the exporter write out the resulting EDIF.<br>
                    <br>
                    <pre style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; color: rgb(0, 0, 0); -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous;"><span style="color: rgb(63, 127, 89);"></span><br><span style="color:#7f0055; ">#</span><span style="color:#7f0055; ">include </span><span style="color:#2a00ff; ">"</span><span style="color:#3f3fbf; ">torc/Generic.hpp</span><span style="color:#2a00ff; ">"</span>
<span style="color:#7f0055; ">#</span><span style="color:#7f0055; ">include </span><span style="color:#2a00ff; ">"</span><span style="color:#3f3fbf; ">torc/Common.hpp</span><span style="color:#2a00ff; ">"</span>
<span style="color:#7f0055; ">#</span><span style="color:#7f0055; ">include </span><span style="color:#2a00ff; ">&lt;</span><span style="color:#3f3fbf; ">fstream</span><span style="color:#2a00ff; ">&gt;</span>
<span style="color:#7f0055; ">#</span><span style="color:#7f0055; ">include </span><span style="color:#2a00ff; ">&lt;</span><span style="color:#3f3fbf; ">boost/regex.hpp</span><span style="color:#2a00ff; ">&gt;</span>

<span style="color:#7f0055; font-weight:bold; ">using</span> <span style="color:#7f0055; font-weight:bold; ">namespace</span> std;
<span style="color:#7f0055; font-weight:bold; ">using</span> <span style="color:#7f0055; font-weight:bold; ">namespace</span> torc::generic;

<span style="color:#7f0055; font-weight:bold; ">int</span> <span style="color:#7f0055; font-weight:bold; ">main</span>(<span style="color:#7f0055; font-weight:bold; ">int</span> argc, <span style="color:#7f0055; font-weight:bold; ">char</span>* argv[]) {

    <span style="color:#3f7f59; ">// build the file paths</span>
    (<span style="color:#7f0055; font-weight:bold; ">void</span>) argc;
    torc::common::DirectoryTree directoryTree(argv[0]);
    boost::filesystem::path referencePath = torc::common::DirectoryTree::getExecutablePath() 
        / <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">regression</span><span style="color:#2a00ff; ">"</span> / <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">GenericExample.reference.edf</span><span style="color:#2a00ff; ">"</span>;
    boost::filesystem::path generatedPath = torc::common::DirectoryTree::getExecutablePath() 
        / <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">regression</span><span style="color:#2a00ff; ">"</span> / <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">GenericExample.generated.edf</span><span style="color:#2a00ff; ">"</span>;

    <span style="color:#3f7f59; ">// import the EDIF design</span>
    <span style="color:#7f0055; font-weight:bold; ">string</span> inFileName = referencePath.<span style="color:#7f0055; font-weight:bold; ">string</span>();
    <span style="color:#7f0055; font-weight:bold; ">fstream</span> fileStream(inFileName.c_str());
    ObjectFactorySharedPtr factoryPtr(<span style="color:#7f0055; font-weight:bold; ">new</span> ObjectFactory());
    EdifImporter importer(factoryPtr);
    importer(fileStream, inFileName);

    <span style="color:#3f7f59; ">// look up an instance of interest</span>
    RootSharedPtr rootPtr = importer.getRootPtr();
    InstanceSharedPtr instancePtr = rootPtr-&gt;findLibrary(<span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">work</span><span style="color:#2a00ff; ">"</span>)-&gt;findCell(<span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">and</span><span style="color:#2a00ff; ">"</span>)
        -&gt;findView(<span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">verilog</span><span style="color:#2a00ff; ">"</span>)-&gt;findInstance(<span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">oZ0</span><span style="color:#2a00ff; ">"</span>);

    <span style="color:#3f7f59; ">// change the INIT property (LUT mask) to XOR</span>
    PropertySharedPtr initPropertyPtr = instancePtr-&gt;getProperty(<span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">INIT</span><span style="color:#2a00ff; ">"</span>);
    <span style="color:#7f0055; font-weight:bold; ">string</span> originalMask = initPropertyPtr-&gt;getValue().get&lt;Value::<span style="color:#7f0055; font-weight:bold; ">String</span>&gt;();
    std::<span style="color:#7f0055; font-weight:bold; ">cout</span> &lt;&lt; <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">The original LUT mask was </span><span style="color:#2a00ff; ">\"</span><span style="color:#2a00ff; ">"</span> &lt;&lt; originalMask &lt;&lt; <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">\"</span><span style="color:#2a00ff; ">.</span><span style="color:#2a00ff; ">"</span> &lt;&lt; std::endl;
    Value xorMask(Value::eValueTypeString, <span style="color:#7f0055; font-weight:bold; ">string</span>(<span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">6</span><span style="color:#2a00ff; ">"</span>));
    initPropertyPtr-&gt;setValue(xorMask);

    <span style="color:#3f7f59; ">// export the EDIF design</span>
    <span style="color:#7f0055; font-weight:bold; ">string</span> outFileName = generatedPath.<span style="color:#7f0055; font-weight:bold; ">string</span>();
    <span style="color:#7f0055; font-weight:bold; ">fstream</span> edifExport(outFileName.c_str(), <span style="color:#7f0055; font-weight:bold; ">ios_base</span>::out);
    EdifExporter exporter(edifExport);
    exporter(rootPtr);

    <span style="color:#7f0055; font-weight:bold; ">return</span> 0;
}
</pre>
                    <br>
                    This example generates little console output, but
                    its effects can be seen with the following diff
                    command:<br>
                    <br>
                    <pre style="margin-left: 40px;">diff regression/GenericExample.reference.edf regression/GenericExample.generated.edf</pre>
                    <br>
                    The expected output is:<br>
                    <br>
                    <pre style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; color: rgb(0, 0, 0); -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous;">47c47<br>&lt;                     (property INIT (string <span style="color: rgb(42, 0, 255);">"</span><span style="color: rgb(42, 0, 255);">8</span><span style="color: rgb(42, 0, 255);">"</span>))<br>---<br>&gt;                     (property INIT (string <span style="color: rgb(42, 0, 255);">"</span><span style="color: rgb(42, 0, 255);">6</span><span style="color: rgb(42, 0, 255);">"</span>))<br></pre>
                    <br>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;"><br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;" class="text4"><a
                      name="PhysicalExample.cpp"></a><img
                      src="images/img4.png" alt="" height="1"
                      width="100%">torc/examples/PhysicalExample.cpp<br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;"><br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">The physical API
                    example reads in an XDL file that must be specified
                    by the user, looks up the design root, and simply
                    writes the design back out.&nbsp; (A few XDL files
                    are available in the regression directory.)<br>
                    <br>
                    Because the input file is specified on the command
                    line, there is no need to create and initialize a
                    DirectoryTree object in this example.&nbsp; The code
                    opens the input file, and then imports all of the
                    XDL into its object mode.&nbsp; The root of that
                    object model is obtained from the importer, and can
                    be used to traverse or manipulate anything in the
                    design.<br>
                    <br>
                    Even though no changes are made to the design in
                    this example, the code is written back out with a
                    different extension.<br>
                    <br>
                    <pre style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; color: rgb(0, 0, 0); -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous;"><span style="color: rgb(63, 127, 89);"></span><br><span style="color:#7f0055; ">#</span><span style="color:#7f0055; ">include </span><span style="color:#2a00ff; ">"</span><span style="color:#3f3fbf; ">torc/Physical.hpp</span><span style="color:#2a00ff; ">"</span>
<span style="color:#7f0055; ">#</span><span style="color:#7f0055; ">include </span><span style="color:#2a00ff; ">&lt;</span><span style="color:#3f3fbf; ">fstream</span><span style="color:#2a00ff; ">&gt;</span>

<span style="color:#3f5fbf; ">/// </span><span style="color:#7f9fbf; font-weight:bold; ">\brief</span><span style="color:#3f5fbf; "> Standard main() function.</span>
<span style="color:#7f0055; font-weight:bold; ">int</span> <span style="color:#7f0055; font-weight:bold; ">main</span>(<span style="color:#7f0055; font-weight:bold; ">int</span> argc, <span style="color:#7f0055; font-weight:bold; ">char</span>* argv[]) {

    <span style="color:#3f7f59; ">// import the XDL design</span>
    <span style="color:#7f0055; font-weight:bold; ">if</span>(argc &lt; 2) <span style="color:#7f0055; font-weight:bold; ">return</span> 0;
    std::<span style="color:#7f0055; font-weight:bold; ">string</span> inFileName(argv[1]);
    std::<span style="color:#7f0055; font-weight:bold; ">fstream</span> fileStream(inFileName.c_str());
    <span style="color:#7f0055; font-weight:bold; ">if</span>(!fileStream.good()) <span style="color:#7f0055; font-weight:bold; ">return</span> -1;
    torc::physical::XdlImporter importer;
    importer(fileStream, inFileName);

    <span style="color:#3f7f59; ">// look up the design (and do something with it ...)</span>
    torc::physical::DesignSharedPtr designPtr = importer.getDesignPtr();

    <span style="color:#3f7f59; ">// export the XDL design</span>
    std::<span style="color:#7f0055; font-weight:bold; ">string</span> outFileName = boost::filesystem::path(inFileName).replace_extension().<span style="color:#7f0055; font-weight:bold; ">string</span>() 
        + <span style="color:#2a00ff; ">"</span><span style="color:#2a00ff; ">.mod.xdl</span><span style="color:#2a00ff; ">"</span>;
    std::<span style="color:#7f0055; font-weight:bold; ">fstream</span> xdlExport(outFileName.c_str(), std::<span style="color:#7f0055; font-weight:bold; ">ios_base</span>::out);
    torc::physical::XdlExporter fileExporter(xdlExport);
    fileExporter(designPtr);

    <span style="color:#7f0055; font-weight:bold; ">return</span> 0;
}
</pre>
                    <br>
                    Note that there is a subclass of
                    torc::physical::XdlImporter named
                    torc::architecture::XdlImporter.&nbsp; The
                    architecture-aware importer identifies the design
                    device, initializes the corresponding device
                    database, and populates all wire usage
                    information.&nbsp; Code like that in the example
                    above, based on torc::architecture::XdlImporter,
                    could serve as the basis for a stand-alone XDL
                    router or placer.<br>
                    <br>
                    This example generates no console output.&nbsp;
                    Modifications to the design are left as an exercise
                    to the reader.<br>
                    <br>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;"><br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;" class="text4"><a
                      name="EdifObfuscator.cpp"></a><img
                      src="images/img4.png" alt="" height="1"
                      width="100%">torc/examples/EdifObfuscator.cpp </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;"><br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">The EDIF obfuscator
                    example reads an EDIF file specified by the user,
                    and replaces all possible names and identifiers with
                    MD5 hashes.&nbsp; Names and identifiers that are not
                    replaced include those in any vendor libraries, as
                    well as those belonging to the design's top-level
                    ports.<br>
                    <br>
                    The sample output below shows a simple EDIF design
                    after obfuscation.&nbsp; The vendor VIRTEX library
                    is not obfuscated, nor are the top-level ports of
                    the design (the "and" cell in this case).<br>
                    <br>
                    <pre style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; color: rgb(0, 0, 0); -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous;">(edif (rename and <span style="color: rgb(42, 0, 255);">"</span><span style="color: rgb(42, 0, 255);">AND</span><span style="color: rgb(42, 0, 255);">"</span>)<br>  (edifVersion 2 0 0)<br>  (edifLevel 0)<br>  (keywordMap (keywordLevel  0))<br>    (library VIRTEX<br>      (ediflevel 0) (technology (numberDefinition))<br>        (cell LUT2<br>          (cellType GENERIC)<br>            (view PRIM<br>              (viewType NETLIST)<br>              (interface <br>                (port I0<br>                  (direction INPUT)<br>                )<br>                (port I1<br>                  (direction INPUT)<br>                )<br>                (port O<br>                  (direction OUTPUT)<br>                )<br>              )<br>            )<br>        )<br>    )<br>    (library work<br>      (ediflevel 0) (technology (numberDefinition))<br>        (cell <br>          (rename and <span style="color: rgb(42, 0, 255);">"</span><span style="color: rgb(42, 0, 255);">AND</span><span style="color: rgb(42, 0, 255);">"</span>)<br>          (cellType GENERIC)<br>            (view verilog<br>              (viewType NETLIST)<br>              (interface <br>                (port i0<br>                  (direction INPUT)<br>                )<br>                (port i1<br>                  (direction INPUT)<br>                )<br>                (port o<br>                  (direction OUTPUT)<br>                )<br>              )<br>              (contents <br>                (instance icfcd208495d565ef66e7dff9f98764da<br>                  (viewRef PRIM (cellRef LUT2 (libraryRef VIRTEX)))<br>                    (property INIT (string ))<br>                )<br>                (net ic4ca4238a0b923820dcc509a6f75849b<br>                  (joined <br>                    (portRef i0)<br>                    (portRef I0 (instanceRef icfcd208495d565ef66e7dff9f98764da))<br>                  )<br>                )<br>                (net ic81e728d9d4c2f636f067f89cc14862c<br>                  (joined <br>                    (portRef i1)<br>                    (portRef I1 (instanceRef icfcd208495d565ef66e7dff9f98764da))<br>                  )<br>                )<br>                (net ieccbc87e4b5ce2fe28308fd9f2a7baf3<br>                  (joined <br>                    (portRef o)<br>                    (portRef O (instanceRef icfcd208495d565ef66e7dff9f98764da))<br>                  )<br>                )<br>              )<br>            )<br>        )<br>    )<br>    (design (rename and <span style="color: rgb(42, 0, 255);">"</span><span style="color: rgb(42, 0, 255);">AND</span><span style="color: rgb(42, 0, 255);">"</span>) (cellRef and (libraryRef work))<br>        (property PART (string <span style="color: rgb(42, 0, 255);">"</span><span style="color: rgb(42, 0, 255);">xc5vlx30ff324-1</span><span style="color: rgb(42, 0, 255);">"</span>)<br>          (owner <span style="color: rgb(42, 0, 255);">"</span><span style="color: rgb(42, 0, 255);">Xilinx</span><span style="color: rgb(42, 0, 255);">"</span>))<br>    )<br>)<br></pre>
                    <br>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;"><br>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td><img src="images/spacer.png" alt="" height="20" width="30"></td>
        </tr>
      </tbody>
    </table>

<?php
	include('footer.php');
?>

</body>
</html>
