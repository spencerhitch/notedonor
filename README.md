# Notedonor
## Description
Notedonor is platform though which symphonic music programs can ask for donatations. Multi-instrument scores transcribed into [vextab](http://www.vexflow.com/vextab/)

A live version of Notedonor is used by [Sinfonía por la vida](www.sinfoniaporlavida.com)

## Dependencies
Make sure you have npm and php installed.

## Usage
Clone the repo, and cd into it.
```
git clone https://github.com/spencerhitch/notedonor.git
cd notedonor/vexflow
```
Install the necessary node package dependencies, make sure that node recognizes THIS vexflow as the one it should use, and build.
```
npm install
npm link
npm start
```
Move to Vextab, install the necessary node package dependencies, make sure vextab is looking to the Vexflow we linked above, build the project (specifically playground.js), and start a local php server on port 8000.
```
cd ../vextab
npm install
npm link vexflow
npm start; grunt playground
php -S localhost:8000
```
In your browser of choice go to `localhost:8000/vextab/tests/playground.html`

## Technologies
Notedonor is built using [npm](https://www.npmjs.com/) and the existing npm packages [Vextab](https://github.com/0xfe/vextab) and [Vexflow](https://github.com/0xfe/vextab). Player functionality is provided through [MIDI.js](https://github.com/mudcube/MIDI.js/) and [soundfonts](https://github.com/gleitz/midi-js-soundfonts) from github user gleitz.

Vextab has been modified to have default enabled note playback and to support multiple instruments. An extra heirarchical layer called a stavegroup acts as the parent to staves. Each stave can be used to represent a different intstrument.

Vexflow has been modified so that stavenotes have a donor_name attribute. When this attribute is non-blank, notes can be hovered to reveal the name. NOTE: This only works because our instance of Vextab has chosesn SVG as its context. Rafael and HTMLCanvas are no longer supported.

MIDI.js remains unmodified.

Notedonor is to be used in tandem with some payment system. We chose Paypal for the Sinfonía project because of Paypal's recognizability in Latin America and its acceptance of Diners Club International Cards (Discover).
