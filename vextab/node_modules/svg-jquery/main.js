'use strict';

var readFileSync = require('fs').readFileSync;

var jquery = require('jquery');
var jsdom = require('jsdom');
var Promise = require('es6-promise').Promise;


/**
 * Returns the given svg as jquery object in a virtual dom context (res.window).
 *
 * @param {String} svg
 * @return {Promise<Object>}
 * @return {Window} resolved.window window object
 * @return {jQuery} resolved.svg svg element as jquery object
 */
var main = function (svg) {

    return new Promise(function (resolve, reject) {

        var html = '<html><head></head><body></body></html>';

        jsdom.env(html, function (err, window) {

            // err can't be exist because html is always the same.
            // so we don't check err == null case

            var $ = jquery(window);

            window.document.body.innerHTML = svg;

            var $svg = $('svg');

            if ($svg.length === 0) {

                reject(new Error('The given string does not seem svg: ' + svg.substring(0, 100)));

                return;

            }

            resolve({
                window: window,
                svg: $svg
            });

        });

    });

};


module.exports = main;
