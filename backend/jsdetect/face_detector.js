var fs = require('fs');

// file is included here:
eval(fs.readFileSync('ccv.js')+'');
eval(fs.readFileSync('face.js')+'');

// ccv.detect_objects({ "canvas" : ccv.grayscale(ccv.pre(image)),
//                                                                  "cascade" : cascade,
//                                                                  "interval" : 5,
//                                                                  "min_neighbors" : 1,
//                                                                  "async" : true,
//                                                                  "worker" : 1 })


var Canvas = require('/usr/local/lib/node_modules/canvas/lib/canvas.js')
  , canvas = new Canvas(200,200)
  , ctx = canvas.getContext('2d');

ctx.font = '30px Impact';
ctx.rotate(.1);
ctx.fillText("Awesome!", 50, 100);

var te = ctx.measureText('Awesome!');
ctx.strokeStyle = 'rgba(0,0,0,0.5)';
ctx.beginPath();
ctx.lineTo(50, 102);
ctx.lineTo(50 + te.width, 102);
ctx.stroke();

console.log('<img src="' + canvas.toDataURL() + '" />');

console.log("hi")
