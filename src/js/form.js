		// Form
		(function () {
			'use strict'
			var forms = document.querySelectorAll('.needs-validation')
			Array.prototype.slice.call(forms)
			.forEach(function (form) {
				form.addEventListener('submit', function (event) {
				if (!form.checkValidity()) {
					event.preventDefault()
					event.stopPropagation()
				}

				form.classList.add('was-validated')
				}, false)
			})
		})();


		// Buttons Profile

		window.onpageshow = function() {
			document.getElementById('avatar').style.display = 'none';
			document.getElementById('password').style.display = 'none';
			};
	
			function edit(){
				document.getElementById('Edit').style.display = 'block';
				document.getElementById('password').style.display = 'none';
				document.getElementById('avatar').style.display = 'none';
			}
	
			function avatar(){
				document.getElementById('avatar').style.display = 'block';
				document.getElementById('Edit').style.display = 'none';
				document.getElementById('password').style.display = 'none';
			}
	
			function password(){
				document.getElementById('password').style.display = 'block';
				document.getElementById('avatar').style.display = 'none';
				document.getElementById('Edit').style.display = 'none';
			}
	
		//Delete Profile
		$('.delete').on('click', function(e){
			e.preventDefault();
			const href = $(this).attr('href')
	
					swal({
					title: "Delete?",
					text: "Do you want to delete?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {
					document.location.href = href;
					}
				});
			})

		//Edit Profile
			$('.edit').on('click', function(e){
				e.preventDefault();
				const href = $(this).attr('href')
			  
					  swal({
					  title: "Edit?",
					  text: "Do you want to edit this data?",
					  icon: "info",
					  buttons: true,
					  dangerMode: true,
					})
					.then((willDelete) => {
					  if (willDelete) {
						document.location.href = href;
					  }
					});
			  })
	

		// Signout
		$('.btn-signout').on('click', function(e){
		e.preventDefault();
		const href = $(this).attr('href')

				swal({
				title: "Signout?",
				text: "Are you sure do you want to signout?",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willSignout) => {
				if (willSignout) {
				document.location.href = href;
				}
			});
		})