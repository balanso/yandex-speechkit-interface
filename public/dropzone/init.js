Dropzone.autoDiscover = false

var dropzone = new Dropzone("form#audio-uploader", {
	url: "/upload",
	success: (file, response) => {
		console.log(file, response)
	},
  acceptedFiles: '.ogg',
})
