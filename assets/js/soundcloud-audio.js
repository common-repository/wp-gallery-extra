(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.SoundCloudAudio = f()}})(function(){var define,module,exports;return (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

var anchor;
var keys = 'protocol hostname host pathname port search hash href'.split(' ');
function _parseURL (url) {
    if (!anchor) {
        anchor = document.createElement('a');
    }
    anchor.href = url || '';
    var result = {}
    for (var i = 0, len = keys.length; i < len; i++) {
        var key = keys[i];
        result[key] = anchor[key];
    }
    return result;
}

function _appendQueryParam (url, param, value) {
    var U = _parseURL(url);
    var regex = /\?(?:.*)$/;
    var chr = regex.test(U.search) ? '&' : '?';
    var result = U.protocol + '//' +  U.host + U.port + U.pathname + U.search + chr + param + '=' + value + U.hash;
    return result;
}

function SoundCloud (clientId) {
    if (!(this instanceof SoundCloud)) {
        return new SoundCloud(clientId);
    }

    if (!clientId) {
        throw new Error('SoundCloud API clientId is required, get it - https://developers.soundcloud.com/');
    }

    this._clientId = clientId;
    this._baseUrl = 'https://api.soundcloud.com';

    this.duration = 0;
}

SoundCloud.prototype.resolve = function (url, callback) {
    if (!url) {
        throw new Error('SoundCloud track or playlist url is required');
    }

    var resolveUrl = this._baseUrl + '/resolve.json?url=' + encodeURIComponent(url) + '&client_id=' + this._clientId;
    this._json(resolveUrl, function (data) {
        this.cleanData();

        if (Array.isArray(data)) {
            var tracks = data;
            data = {tracks: tracks};
            this._playlist = data;
        } else if (data.tracks) {
            this._playlist = data;
        } else {
            this._track = data;

            // save timings
            var U = _parseURL(url);
            this._track.stream_url += U.hash;
            this._track.download_url += '?client_id=' + this._clientId;
            this._track.stream_url += '?client_id=' + this._clientId;
        }

        this.duration = data.duration && !isNaN(data.duration) ?
            data.duration / 1000 : // convert to seconds
            0; // no duration is zero

        callback(data);
    }.bind(this));
};

// deprecated
SoundCloud.prototype._jsonp = function (url, callback) {
    var target = document.getElementsByTagName('script')[0] || document.head;
    var script = document.createElement('script');

    var id = 'jsonp_callback_' + (new Date()).valueOf() + Math.floor(Math.random() * 1000);
    window[id] = function (data) {
        if (script.parentNode) {
            script.parentNode.removeChild(script);
        }
        window[id] = function () {};
        callback(data);
    };

    script.src = _appendQueryParam(url, 'callback', id);
    target.parentNode.insertBefore(script, target);
};

SoundCloud.prototype._json = function (url, callback) {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', url);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var resp = {};
        try {
            resp = JSON.parse(xhr.responseText);
        } catch (err) {
            // fail silently
        }
        callback(resp);
      }
    }
  };
  xhr.send(null);
};

SoundCloud.prototype.cleanData = function () {
    this._track = void 0;
    this._playlist = void 0;
};

module.exports = SoundCloud;

},{}]},{},[1])(1)
});