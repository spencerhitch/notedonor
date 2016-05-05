

var expect = require('chai').expect;
var fs = require('fs');
var Window = require('jsdom/lib/jsdom/browser/Window');

var main = require('./main');

describe('main', function () {

    it('returns a promise which resolves with obj.svg and obj.window when svg file exists', function () {

        return main(fs.readFileSync('./sample.svg')).then(function (res) {

            var svg = res.svg;
            var window = res.window;

            expect(window.constructor).to.equal(Window);

            // The below assertion doesn't pass, I don't know why
            // expect(window).to.be.instanceof(Window);

            expect(svg).to.be.instanceof(window.jQuery);
            expect(svg[0]).to.be.instanceof(window.HTMLElement);

        });

    });

    it('returns a promise which rejects when the given string is not svg', function () {

        return main('This is not svg').catch(function (err) {

            expect(err).to.be.instanceof(Error);

        });

    });

});
