<style>
@font-face {
  font-family: 'CalibriRegular';
  src: url('{{ url("/") }}/assets/css/fonts/CalibriRegular.ttf') format("truetype");
  font-weight: 400;
  font-style: normal; 
}
@font-face {
  font-family: 'CalibriBold';
  src: url('{{ url("/") }}/assets/css/fonts/CalibriBold.ttf') format("truetype");
  font-weight: 700;
  font-style: normal; 
}

body {
  color: #1d426b;
  font-size: 11pt;
  font-family: 'CalibriRegular', Helvetica, Arial, sans-serif;
  line-height: .9;
  font-weight: 400;
  text-rendering: optimizeLegibility;
}

.content {
  margin-top: 60mm;
  position: relative;
  width: 171mm;
  z-index: 100;
}

strong {
  font-family: 'CalibriBold', Helvetica, Arial, sans-serif;
  font-weight: 700;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
}

td {
  padding: 0;
}

img {
  border: 0;
  vertical-align: middle;
}

table {
  width: 100%;
}

table td {
  text-align: left;
  vertical-align: top;
}

h1, h2, h3 {
  font-family: 'CalibriBold', Helvetica, Arial, sans-serif;
  font-weight: 700;
}

.align-right {
  text-align: right;
}

.align-left {
  text-align: left;
}

.clearfix:after {
  visibility: hidden;
  display: block;
  font-size: 0;
  content: " ";
  clear: both;
  height: 0;
}

.logo {
  display: inline-block;
  height: 22mm;
  left: 0;
  position: fixed;
  top: 13mm;
  width: 171mm;
  z-index: 100;
}

.logo img {
  display: block;
  height: auto;
  width: 100%;
}

.break {
  page-break-after: always;
}

</style>