# svg-jquery v1.0.3 [![Build Status](https://travis-ci.org/kt3k/svg-jquery.svg?branch=master)](https://travis-ci.org/kt3k/svg-jquery)

> Get svg as jquery object in jsdom env on node.js.

# Install

```sh
npm install svg-jquery
```

# Usage

```js
var parseSvg = require('svg-jquery')

parseSvg('<svg xmlns="http://www.w3.org/2000/svg"><rect fill="black" x="50" y="50" width="100" height="100"></rect></svg>').then(function (res) {

    var window = res.window;
    var jQuery = res.window.$;

    var svg = res.svg; // This is the same as `res.window.$('svg')`

    var rect = svg.find('rect');

    // ... any manipulations on svg

}).catch(function (err) {

    // the case svg is broken

});

```


# License

MIT
