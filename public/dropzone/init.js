Dropzone.autoDiscover = false

var dropzone = new Dropzone("form#audio-uploader", {
	url: "/upload",
	timeout: 0,
	success: (file, response) => {
		console.log(file, response)
	},
  acceptedFiles: '.ogg,.opus',
})
