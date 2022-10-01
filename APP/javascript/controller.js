//coommunicates with php and gets a result to be displayed as a view
class xhttp {
	open_port() {
		this.xhttp = new XMLHttpRequest();
		this.xhttp.open('POST', '/I/point_update', true);
		this.xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	}
}
//
//gets information from the model to display 
class view {
	//loading
	alert_loading() {
		swal.fire({
			text: 'loading...',
			showConfirmButton: false,
		});
	};
	//Processing
	alert_processing() {
		swal.fire({
			text: 'Processing...',
			showConfirmButton: false,
		});
	};
	//
	alert_popup(msg) {
		swal.fire({
			text: msg,
			type: 'success',
			timer: 900,
			showConfirmButton: false,
		});
	};
	///popup_info
	alert_popup_info(msg) {
		swal.fire({
			html: msg,
			type: 'info',
			showConfirmButton: true,
		});
	};
	//popup info refresh
	alert_popup_info_fresh(msg) {
		swal.fire({
			html: msg,
			type: 'info',
			showConfirmButton: true,
		}).then(function () {

			location.reload();
		});
	};
	//popup with refresh
	alert_popup_fresh(msg) {
		swal.fire({
			text: msg,
			type: 'success',
			timer: 1300,
			showConfirmButton: false,
		}).then(function () {
			location.reload();
		});
	};
	//popup with refresh
	alert_popup_freshome(msg) {
		swal.fire({
			text: msg,
			type: 'success',
			timer: 1300,
			showConfirmButton: false,
		}).then(function () {
			window.location.href = "//PracticePractice.net";
		});
	};
	//
	//popup with refresh
	headder_redirect(link) {
		swal.fire({
			text: 'redirecting...',
			timer: 800,
			showConfirmButton: false,
		}).then(function () {
			window.location.href = link;
		});
	};
	//
	new_headder_redirect(link) {
		swal.fire({
			text: 'redirecting...',
			timer: 500,
			showConfirmButton: false,
		}).then(function () {
			window.open(link,'_blank');
		});
	};
	//
	straight_headder_redirect(link) {
		window.location.href = link;
	};
	//
	alert_warning(msg) {
		swal.fire({
			text: msg,
			type: 'warning',
			showConfirmButton: true,
		});
	};
	//warning to confirm action
	alert_warning_orCancel(msg, func) {
		swal.fire({
			title: msg,
			text: "You won't be able to revert this!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Delete it!',
			cancelButtonText: 'No, cancel!',
			reverseButtons: true
		}).then((result) => {
			if (result.value) {
				swal.fire({
					text: msg,
					type: 'info',
					timer: 800,
					showConfirmButton: false
				})
				func();
			} else if (
				// Read more about handling dismissals
				result.dismiss === Swal.DismissReason.cancel
			) {

				swal.fire(
					'Cancelled',
					'Nothing was done :)',
					'info'
				)
			}
		})
	}
	//warning to confirm action
	alert_warning_orCancel_go(msg, func) {
		swal.fire({
			title: msg,
			text: "You won't be able to revert this!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Do it!',
			cancelButtonText: 'No, cancel!',
			reverseButtons: true
		}).then((result) => {
			if (result.value) {
				swal.fire({
					text: msg,
					type: 'info',
					timer: 800,
					showConfirmButton: false
				})
				func();
			} else if (
				// Read more about handling dismissals
				result.dismiss === Swal.DismissReason.cancel
			) {

				swal.fire(
					'Cancelled',
					'Nothing was done :)',
					'info'
				)
			}
		})
	}
	alert_error(msg) {
		swal.fire({
			text: msg,
			type: 'error',
			showConfirmButton: true,
		});
	};
	//display error and return to previous page
	alert_error_previous_page(msg) {
		swal.fire({
			text: msg,
			type: 'error',
			showConfirmButton: true,
		}).then(function () {
			history.back();
		})
	};
	//figure generators
	charts_impressions(xs,ys){
		var ctx = document.getElementById('impressions_chart');
		//
		var xlabel = xs;
		var data1 = ys;
		//
		var myChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: xlabel,
				datasets: [{
					label: 'Live impressions today',
					backgroundColor: 'red',
					borderColor: 'red',
					data: data1,
					fill: false
				}
			]},
			options: {
				legend: {
						position: 'top',
					},
				responsive: true,
				title: {
					display: true,
					text: 'Impressions'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Time'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Site impressions'
						}
					}]
				}
			}
			
		});
	}
	//figure generators
	charts_performance(xs,ys){
		var ctx = document.getElementById('performance_chart');
		//
		var xlabel = xs;
		var data1 = ys;
		//
		var myChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: xlabel,
				datasets: [{
					label: 'Score',
					backgroundColor: 'red',
					borderColor: 'red',
					data: data1,
					fill: false
				}
				
			]},
			options: {
				legend: {
						position: 'top',
					},
				responsive: true,
				title: {
					display: true,
					text: 'Average Question Performance Index'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Time (days ago)'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Average Index'
						},
						ticks: {
							min: 0,
							max: 1
						}
					}]
				}
			}
			
		});
	}

}









//coommunicates with php and gets a result to be displayed as a view
class model extends xhttp {
	//
	M_add_newSpec_moduel(subject, moduel) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'add_newSpec_moduel=1&subject=' + subject + '&moduel=' + moduel;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_update_topicField_selection(subject, moduel) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'update_topicField_selection=1&subject=' + subject + '&moduel=' + moduel;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_delete_mylist_items() {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'delete_mylist_items=1';
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_Reset_userPassword(pwd_current, pwd_new, pwd_new2) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'reset_userPassword=1&current=' + pwd_current + '&new=' + pwd_new + '&new2=' + pwd_new2;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_update_topicField_selection_question(subject, moduel) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'update_topicField_selection_question=1&subject=' + subject + '&moduel=' + moduel;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_update_moduel(subject, moduel, new_moduel, chapter) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'update_moduel_name=1&subject=' + subject + '&moduel=' + moduel + '&new_input=' + new_moduel + '&chapter=' + chapter;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_update_chapter(subject, moduel, chapter, new_chapter) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'update_chapter_name=1&subject=' + subject + '&moduel=' + moduel + '&chapter=' + chapter + '&new_input=' + new_chapter;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_add_newSpec_chapter(subject, moduel, chapter) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'add_newSpec_chapter=1&subject=' + subject + '&moduel=' + moduel + '&chapter=' + chapter;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_update_topic(subject, moduel, chapter, topic, new_topic) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'update_topic_name=1&subject=' + subject + '&moduel=' + moduel + '&chapter=' + chapter + '&topic=' + topic + '&new_input=' + new_topic;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_update_topic_editor(subject, moduel, chapter, topic, new_topic) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'editor_updates=1&update_topic_name=1&subject=' + subject + '&moduel=' + moduel + '&chapter=' + chapter + '&topic=' + topic + '&new_input=' + new_topic;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_add_newSpec_topic(subject, moduel, chapter, topic) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'add_newSpec_topic=1&subject=' + subject + '&moduel=' + moduel + '&chapter=' + chapter + '&topic=' + topic;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_add_newSpec_topic_editor(subject, moduel, chapter, topic) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'editor_updates=1&add_newSpec_topic=1&subject=' + subject + '&moduel=' + moduel + '&chapter=' + chapter + '&topic=' + topic;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_update_description(unique_id, description) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'point_id=' + unique_id + '&new_input=' + encodeURIComponent(description);
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_update_description_editor(unique_id, description) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'editor_updates=1&point_id=' + unique_id + '&new_input=' + encodeURIComponent(description);
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_add_newSpec_point(point_unique_id) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'add_new_point=1&point_unique_id=' + point_unique_id;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_add_newSpec_point_editor(point_unique_id) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'editor_updates=1&add_new_point=1&point_unique_id=' + point_unique_id;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_delete_moduel(moduel_unique_id) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'delete_moduel_specpage=1&unique_id=' + moduel_unique_id;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_delete_chapter(point_unique) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'delete_chapter_specpage=' + point_unique;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_delete_topic(point_unique) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'delete_topic_specpage=' + point_unique;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_delete_topic_editor(point_unique) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'editor_updates=1&delete_topic_specpage=' + point_unique;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_remove_Spec_point(point_unique_id) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'remove_point=1&rm_point_unique_id=' + point_unique_id;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_remove_Spec_point_editor(point_unique_id) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'editor_updates=1&remove_point=1&rm_point_unique_id=' + point_unique_id;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_uploadFile(file, point_id) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = new FormData();
		//
		query.append('file1', file);
		query.append('image_specform_upload', 1);
		query.append('pt_id', point_id);
		//
		this.xhttp.send(query);

	}
	//
	M_delete_file(unique, filename) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'delete_file_unique=1&point_unique=' + encodeURIComponent(unique) + '&filename=' + filename;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_delete_file_qs(unique, filename) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'delete_file_unique_qs=1&question_unique=' + encodeURIComponent(unique) + '&filename=' + filename;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_delete_file_editor(unique, filename) {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'editor_updates=1&delete_file_unique=1&point_unique=' + encodeURIComponent(unique) + '&filename=' + filename;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_delete_html_cache() {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'delete_cache_html=1';
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_specpoint_DBprocessing() {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'specpoint_Dbprocessing=1';
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_question_DBprocessing() {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'question_DBprocessing=1';
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_paper_DBprocessing() {
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'paper_DBprocessing=1';
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_user_login(uid, pwd) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'user_login=1&uid=' + uid + '&pwd=' + pwd;
		//
		this.xhttp.send(query);
	}
	//
	M_user_logoff() {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'user_logoff=1';
		//
		this.xhttp.send(query);
	}
	//
	M_user_recover_password(account_email) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'user_recover_password=1&email=' + account_email;
		//
		this.xhttp.send(query);
	}
	//
	M_register_new_pwd(vkey, pwd1, pwd2) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'apply_new_pwd=1&vkey=' + vkey + '&pwd1=' + pwd1 + '&pwd2=' + pwd2;
		//
		this.xhttp.send(query);
	}
	//
	M_user_newsignup(username, firstname, lastname, email1, email2, pwd1, pwd2) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'user_newsignup=1&username=' + username + '&firstname=' + firstname + '&lastname=' + lastname + '&email1=' + email1 + '&email2=' + email2 + '&pwd1=' + pwd1 + '&pwd2=' + pwd2;
		//
		this.xhttp.send(query);
	}
	//
	M_verify_newuser(key) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'verify_newuser=1&verification_key=' + key;
		//
		this.xhttp.send(query);
	}
	//
	M_user_list_specfilter(subject, user_unique_id) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'user_list_specfilter=1&pt_subject=' + subject + '&user_unique=' + user_unique_id;
		//
		this.xhttp.send(query);
	}
	//
	M_update_raw_spec_info(subject, moduel, chapter, pt_information) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'update_pt_raw_information=1&pt_subject=' + subject + '&pt_moduel=' + moduel + '&pt_chapter=' + chapter + '&pt_information=' + encodeURIComponent(pt_information);
		//
		this.xhttp.send(query);
	}
	//
	M_AssignEditor(subject, moduel, chapter, value) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'assigneditor=1&pt_subject=' + subject + '&pt_moduel=' + moduel + '&pt_chapter=' + chapter + '&task_payment_amount='+ value;
		//
		this.xhttp.send(query);
	}
	//
	M_hireuser(user_unique, email, primary_subject, secondary_subject) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'hireuser_toeditor=1&user_unique_id=' + user_unique + '&email=' + email + '&primary_subject=' + primary_subject + '&secondary_subject=' + secondary_subject;
		//
		this.xhttp.send(query);
	}
	//
	M_editor_cancel_contract(editor_unique_id) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'editor_cancelself_contract=1&user_unique_id=' + editor_unique_id;
		//
		this.xhttp.send(query);
	}
	//
	M_editor_suspension(editor_unique_id) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'editor_suspension=1&user_unique_id=' + editor_unique_id;
		//
		this.xhttp.send(query);
	}
	//
	M_editor_claimtask(editor_unique_id, pt_subject, pt_moduel, pt_chapter) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'editor_claimtask=1&editor_unique_id=' + editor_unique_id + '&pt_subject=' + pt_subject + '&pt_moduel=' + pt_moduel + '&pt_chapter=' + pt_chapter;
		//
		this.xhttp.send(query);
	}
	//
	M_editor_droptask(editor_unique_id, pt_subject, pt_moduel, pt_chapter) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'editor_droptask=1&editor_unique_id=' + editor_unique_id + '&pt_subject=' + pt_subject + '&pt_moduel=' + pt_moduel + '&pt_chapter=' + pt_chapter;
		//
		this.xhttp.send(query);
	}
	//
	M_editor_droptask_question(user_unique, subject, moduel, chapter, topic, point) {
		//opening the port using xhttp
		this.open_port();
		//
		//
		if (topic == '' && point == '') {
			//
			var query = 'editor_droptask_question=1&editor_unique_id=' + user_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter;
		} else if (point == '') {
			//
			var query = 'editor_droptask_question=1&editor_unique_id=' + user_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic;
		} else {
			//
			var query = 'editor_droptask_question=1&editor_unique_id=' + user_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&q_point=' + point;
		}
		//
		this.xhttp.send(query);
	}
	//
	M_unlist_editor_task(pt_subject, pt_moduel, pt_chapter) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'unlist_editor_task=1&pt_subject=' + pt_subject + '&pt_moduel=' + pt_moduel + '&pt_chapter=' + pt_chapter;
		//
		this.xhttp.send(query);
	}
	//
	M_letEditor_work(editor_unique_id, pt_subject, pt_moduel, pt_chapter) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'letEditor_work=1&editor_unique_id=' + editor_unique_id + '&pt_subject=' + pt_subject + '&pt_moduel=' + pt_moduel + '&pt_chapter=' + pt_chapter;
		//
		this.xhttp.send(query);
	}
	//
	M_editor_submit_work(editor_unique_id, pt_subject, pt_moduel, pt_chapter) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'editor_submit_work=1&editor_unique_id=' + editor_unique_id + '&pt_subject=' + pt_subject + '&pt_moduel=' + pt_moduel + '&pt_chapter=' + pt_chapter;
		//
		this.xhttp.send(query);
	}
	//
	M_submit_work_question(subject, moduel, chapter, topic, point) {
		//opening the port using xhttp
		this.open_port();
		//
		if (topic == '' && point == '') {
			//
			var query = 'submit_work_question=1&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter;
		} else if (point == '') {
			//
			var query = 'submit_work_question=1&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic;
		} else {
			//
			var query = 'submit_work_question=1&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&q_point=' + point;
		}
		//
		this.xhttp.send(query);
	}
	//
	M_admin_review_redirect(editor_unique_id, pt_subject, pt_moduel, pt_chapter) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'admin_review_redirect=1&editor_unique_id=' + editor_unique_id + '&pt_subject=' + pt_subject + '&pt_moduel=' + pt_moduel + '&pt_chapter=' + pt_chapter;
		//
		this.xhttp.send(query);
	}
	//
	M_admin_review_redirect_question(editor_unique, subject, moduel, chapter, topic, point) {
		//opening the port using xhttp
		this.open_port();
		//
		if (topic == '' && point == '') {
			//
			var query = 'admin_review_redirect_question=1&editor_unique_id=' + editor_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter;
		} else if (point == '') {
			//
			var query = 'admin_review_redirect_question=1&editor_unique_id=' + editor_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic;
		} else {
			//
			var query = 'admin_review_redirect_question=1&editor_unique_id=' + editor_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&q_point=' + point;
		}
		this.xhttp.send(query);
	}
	//
	M_admin_accept_work(editor_unique_id, pt_subject, pt_moduel, pt_chapter) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'admin_accept_work=1&editor_unique_id=' + editor_unique_id + '&pt_subject=' + pt_subject + '&pt_moduel=' + pt_moduel + '&pt_chapter=' + pt_chapter;
		//
		this.xhttp.send(query);
	}
	//
	M_accept_work_question(editor_unique, subject, moduel, chapter, topic, q_point) {
		//opening the port using xhttp
		this.open_port();
		//
		if (topic == '' && q_point == '') {
			//
			var query = 'admin_accept_work_question=1&editor_unique_id=' + editor_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter;
		} else if (q_point == '') {
			//
			var query = 'admin_accept_work_question=1&editor_unique_id=' + editor_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic;
		} else {
			//
			var query = 'admin_accept_work_question=1&editor_unique_id=' + editor_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&pt_unique_id=' + q_point;
		}
		//
		this.xhttp.send(query);
	}
	//
	M_admin_reject_work(editor_unique_id, pt_subject, pt_moduel, pt_chapter) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'admin_reject_work=1&editor_unique_id=' + editor_unique_id + '&pt_subject=' + pt_subject + '&pt_moduel=' + pt_moduel + '&pt_chapter=' + pt_chapter;
		//
		this.xhttp.send(query);
	}
	//
	M_reject_work_question(editor_unique, subject, moduel, chapter, topic, point_unique) {
		//opening the port using xhttp
		this.open_port();
		//
		if (topic == '' && point_unique == '') {
			//
			var query = 'reject_work_question=1&editor_unique_id=' + editor_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter;
		} else if (point_unique == '') {
			//
			var query = 'reject_work_question=1&editor_unique_id=' + editor_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic;
		} else {
			//
			var query = 'reject_work_question=1&editor_unique_id=' + editor_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&pt_unique_id=' + point_unique;
		}
		//
		this.xhttp.send(query);
	}
	//
	M_see_questions(filter_type, pt_unique_id) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'question_page_redirect=1&q_filter_type=' + filter_type + '&pt_unique_id=' + pt_unique_id;
		//
		this.xhttp.send(query);
	}
	//
	M_create_new_question(filter, subject, moduel, chapter, topic, point_unique) {
		//opening the port using xhttp
		this.open_port();
		//
		if (filter == 'chapter') {
			//
			var query = 'add_new_question=1&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&filter=' + filter;
		} else if (filter == 'topic') {
			//
			var query = 'add_new_question=1&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&filter=' + filter;
		} else {
			if (filter == 'point') {
				//
				var query = 'add_new_question=1&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&pt_unique_id=' + point_unique + '&filter=' + filter;
			}
		}
		//
		this.xhttp.send(query);
	}
	//
	M_create_new_question_editor(filter, subject, moduel, chapter, topic, point_unique) {
		//opening the port using xhttp
		this.open_port();
		//
		if (filter == 'chapter') {
			//
			var query = 'add_new_question_editor=1&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&filter=' + filter;
		} else if (filter == 'topic') {
			//
			var query = 'add_new_question_editor=1&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&filter=' + filter;
		} else {
			if (filter == 'point') {
				//
				var query = 'add_new_question_editor=1&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&pt_unique_id=' + point_unique + '&filter=' + filter;
			}
		}
		//
		this.xhttp.send(query);
	}
	//
	M_update_question_info(question_unique, description) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'update_question_info=1&q_unique_id=' + question_unique + '&new_input=' + encodeURIComponent(description);
		//
		this.xhttp.send(query);
	}
	//
	M_update_question_info_editor(question_unique, description) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'update_question_info_editor=1&q_unique_id=' + question_unique + '&new_input=' + encodeURIComponent(description);
		//
		this.xhttp.send(query);
	}
	//
	M_delete_question(question_unique) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'delete_question=1&q_unique_id=' + question_unique;
		//
		this.xhttp.send(query);
	}
	//
	M_delete_question_editor(question_unique) {
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'delete_question_editor=1&q_unique_id=' + question_unique;
		//
		this.xhttp.send(query);
	}
	//
	M_assign_editor_question_section(filter, subject, moduel, chapter, topic, point, value) {
		//opening the port using xhttp
		this.open_port();
		//
		if (filter == 'chapter') {
			//
			var query = 'assign_editor_question=1&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&filter=' + filter;
		} else if (filter == 'topic') {
			//
			var query = 'assign_editor_question=1&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&filter=' + filter;
		} else {
			if (filter == 'point') {
				//
				var query = 'assign_editor_question=1&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&pt_unique_id=' + point + '&filter=' + filter;
			}
		}
		//
		var query = query + '&task_payment_amount='+ value;
		//
		this.xhttp.send(query);
	}
	//
	M_unlist_editor_task_question(subject, moduel, chapter, topic, point) {
		//opening the port using xhttp
		this.open_port();
		//
		if (topic == '' && point == '') {
			//
			var query = 'unlist_editor_task_question=1&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter;
		} else if (point == '') {
			//
			var query = 'unlist_editor_task_question=1&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic;
		} else {
			//
			var query = 'unlist_editor_task_question=1&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&q_point=' + point;
		}
		//
		this.xhttp.send(query);
	}
	//
	M_editor_claimtask_question(user_unique, subject, moduel, chapter, topic, point) {
		//opening the port using xhttp
		this.open_port();
		//
		if (topic == '' && point == '') {
			//
			var query = 'editor_claimtask_question=1&editor_unique_id=' + user_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter;
		} else if (point == '') {
			//
			var query = 'editor_claimtask_question=1&editor_unique_id=' + user_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic;
		} else {
			//
			var query = 'editor_claimtask_question=1&editor_unique_id=' + user_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&q_point=' + point;
		}
		//
		this.xhttp.send(query);
	}
	//
	M_letEditor_work_question(user_unique, subject, moduel, chapter, topic, point) {
		//opening the port using xhttp
		this.open_port();
		//
		if (topic == '' && point == '') {
			//
			var query = 'letEditor_work_question=1&editor_unique_id=' + user_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter;
		} else if (point == '') {
			//
			var query = 'letEditor_work_question=1&editor_unique_id=' + user_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic;
		} else {
			//
			var query = 'letEditor_work_question=1&editor_unique_id=' + user_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&q_point=' + point;
		}
		//
		this.xhttp.send(query);
	}
	//
	M_initiate_membership_payment(membership_option){
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'initiate_membership_payment=1&membership_option='+membership_option;
		//
		this.xhttp.send(query);
	}
	//
	M_finalise_membership_payment(token,payer_id){
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'finalise_membership_payment=1&paypal_token='+encodeURIComponent(token)+'&paypal_payer_id='+encodeURIComponent(payer_id);
		//
		this.xhttp.send(query);
	}
	//
	M_cancel_membership_payment(){
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'cancel_membership_payment=1';
		//
		this.xhttp.send(query);
	}
	//
	M_initiate_editor_payout(user_unique, subject, moduel, chapter){
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'initiate_editor_payout=1&editor_unique_id=' + user_unique + '&pt_subject=' + subject + '&pt_moduel=' + moduel + '&pt_chapter=' + chapter;
		//
		this.xhttp.send(query);
	}
	//
	M_initiate_editor_payout_bnkak(user_unique, subject, moduel, chapter){
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'initiate_editor_payout_bnkak=1&editor_unique_id=' + user_unique + '&pt_subject=' + subject + '&pt_moduel=' + moduel + '&pt_chapter=' + chapter;
		//
		this.xhttp.send(query);
	}
	//
	M_initiate_editor_payout_question(user_unique, subject, moduel, chapter, topic, point){
		//opening the port using xhttp
		this.open_port();
		//
		if (topic == '' && point == '') {
			//
			var query = 'initiate_editor_payout_question=1&editor_unique_id=' + user_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter;
		} else if (point == '') {
			//
			var query = 'initiate_editor_payout_question=1&editor_unique_id=' + user_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic;
		} else {
			//
			var query = 'initiate_editor_payout_question=1&editor_unique_id=' + user_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&q_point=' + point;
		}
		//
		this.xhttp.send(query);
	}
	//
	M_initiate_editor_payout_question_bnkak(user_unique, subject, moduel, chapter, topic, point){
		//opening the port using xhttp
		this.open_port();
		//
		if (topic == '' && point == '') {
			//
			var query = 'initiate_editor_payout_question_bnkak=1&editor_unique_id=' + user_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter;
		} else if (point == '') {
			//
			var query = 'initiate_editor_payout_question_bnkak=1&editor_unique_id=' + user_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic;
		} else {
			//
			var query = 'initiate_editor_payout_question_bnkak=1&editor_unique_id=' + user_unique + '&q_subject=' + subject + '&q_moduel=' + moduel + '&q_chapter=' + chapter + '&q_topic=' + topic + '&q_point=' + point;
		}
		//
		this.xhttp.send(query);
	}
	//
	M_editor_bnkakaccount_input(editor_bnkak_id){
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'editor_bnkak_input=' + encodeURIComponent(editor_bnkak_id);
		//
		this.xhttp.send(query);
	}
	//
	M_editor_paypalemail_input(editor_paypal_id){
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'editor_paypalemail_input=' + encodeURIComponent(editor_paypal_id);
		//
		this.xhttp.send(query);
	}
	//
	M_editor_paypalverification_input(editor_paypalverification){
		//opening the port using xhttp
		this.open_port();
		//
		var query = 'editor_paypalverification_input=' + encodeURIComponent(editor_paypalverification);
		//
		this.xhttp.send(query);
	}
	//
	M_make_mindmap(subject,moduel,chapter){
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'make_mindmap=1&pt_subject=' + subject + '&pt_moduel=' + moduel +'&pt_chapter=' + chapter;
		//
		if (moduel == '' && chapter == '') {
			//
			var query = 'make_mindmap=1&pt_subject=' + subject
		} else if (chapter == '') {
			//
			var query = 'make_mindmap=1&pt_subject=' + subject + '&pt_moduel=' + moduel 
		} else {
			//
			var query = 'make_mindmap=1&pt_subject=' + subject + '&pt_moduel=' + moduel +'&pt_chapter=' + chapter;
		}
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_alert_editor_work(){
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'alert_editors_for_work=1';
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_publish_moduel(subject,moduel){
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'publish_moduel=1&pt_subject='+subject+'&pt_moduel='+moduel;
		//sending query to model
		this.xhttp.send(query);
	}	
	//
	M_unpublish_moduel(subject,moduel){
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'unpublish_moduel=1&pt_subject='+subject+'&pt_moduel='+moduel;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_delete_cache(){
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'delete_cache=1';
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_impression_track(){
		//
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'track_impressions=1';
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_get_impressions_data(){
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'get_impressions_data=1';
		//sending query to model
		this.xhttp.send(query);	
	}
	//
	M_get_performance_data(){
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'get_performance_data=1';
		//sending query to model
		this.xhttp.send(query);	
	}
	//
	M_contact_form(name, to_email, subject, message){
		//opening the port using xhttp
		this.open_port();
		//prepairing query
		var query = 'contact_form=1&name='+encodeURIComponent(name)+'&email='+encodeURIComponent(to_email)+'&con_subject='+encodeURIComponent(subject)+'&message='+encodeURIComponent(message);
		//sending query to model
		this.xhttp.send(query);	
	}
	//
	M_get_custom_practice(subject,moduel,chapter,type,diff){
		this.open_port();
		//prepairing query
		var query = 'q_subject='+subject+'&q_moduel='+moduel+'&q_chapter='+chapter+'&q_type='+type+'&q_difficulty='+diff;
		//sending query to model
		this.xhttp.send(query);	
	}
	//
	M_mark_question(q_unique_id,mark,total){
		this.open_port();
		//prepairing query
		var query = 'mark_question=1&q_unique_id='+q_unique_id +'&mark='+mark+'&total_mark='+total;
		//sending query to model
		this.xhttp.send(query);	
	}
	//
	M_toggle_question_visibility(){
		this.open_port();
		//prepairing query
		var query = 'toggle_visibility=1';
		//sending query to model
		this.xhttp.send(query);	
	}	
	//
	M_toggle_level(user_unique_id){
		this.open_port();
		//prepairing query
		var query = 'toggle_level=1';
		//sending query to model
		this.xhttp.send(query);	
	}
	//
	M_locate_question(question_unique){
		this.open_port();
		//prepairing query
		var query = 'locate_unique_question=1&q_unique_id='+question_unique;
		//sending query to model
		this.xhttp.send(query);
	}
//
	M_make_progress_bar(subject,moduel,chapter,topic){
		//
		this.open_port();
		//prepairing query
		if (moduel == '' && chapter == '' && topic == '') {
			//
			var query = 'make_progress_bar=1&pt_subject=' + subject
		} else if (chapter == '' && topic =='') {
			//
			var query = 'make_progress_bar=1&pt_subject=' + subject + '&pt_moduel=' + moduel 
		} else {
			//
			if(topic == ''){
				var query = 'make_progress_bar=1&pt_subject=' + subject + '&pt_moduel=' + moduel +'&pt_chapter=' + chapter;
			}else{
				var query = 'make_progress_bar=1&pt_subject='+subject+'&pt_moduel='+moduel+'&pt_chapter='+chapter+'&pt_topic='+topic;
			}
		}
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_apply_discount_code(code){
		this.open_port();
		//prepairing query
		var query = 'apply_discount_code=1&discount_code='+encodeURI(code);
		//sending query to model
		this.xhttp.send(query);	
	}	
	//
	M_change_membership_option(option){
		this.open_port();
		//prepairing query
		var query = 'change_membership_option=1&membership_option='+encodeURI(option);
		//sending query to model
		this.xhttp.send(query);	
	}
	//
	M_organise_paper(paper_name){
		this.open_port();
		//prepairing query
		var query = 'organise_paper_input=1&paper_detailed_name='+encodeURI(paper_name);
		//sending query to model
		this.xhttp.send(query);	
	}
	//
	M_relocate_question_files(q_unique_id,pt_unique_id){
		//
		this.open_port();
		//prepairing query
		var query = 'relocate_question_files=1&q_unique_id='+encodeURI(q_unique_id)+'&pt_unique_id='+encodeURI(pt_unique_id);
		//sending query to model
		this.xhttp.send(query);	
	}
	//
	M_relocate_question_files2(q_unique_id,pt_unique_id){
		//
		this.open_port();
		//prepairing query
		var query = 'relocate_question_files2=1&q_unique_id='+encodeURI(q_unique_id)+'&pt_unique_id='+encodeURI(pt_unique_id);
		//sending query to model
		this.xhttp.send(query);	
	}
	//
	M_affiliate_payout(q_unique_id,pt_unique_id){
		//
		this.open_port();
		//prepairing query
		var query = "affiliate_payout=1";
		//sending query to model
		this.xhttp.send(query);	
	}
	//
	M_create_flashcard(pt_unique_id,question,answer){
		//
		this.open_port();
		//prepairing query
		var query = "create_flashcard="+pt_unique_id+"&flash_question="+encodeURI(question)+"&flash_answer="+encodeURI(answer);
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_create_review(name,review,rating){
		//
		this.open_port();
		//prepairing query
		var query = "create_review=1"+'&reviewer_name='+name+'&reviewer_rating='+rating+'&reviewer_review='+review
		//sending query to model
		this.xhttp.send(query);
	}

	//
	M_delete_flashcard(fc_id){
		//
		this.open_port();
		//prepairing query
		var query = "delete_flashcard="+fc_id;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_mark_fc(status,id){
		//
		this.open_port();
		//prepairing query
		var query = "mark_flashcard="+status+"&flashcard_id="+id;
		//sending query to model
		this.xhttp.send(query);
	}
	//
	M_reset_flashcards(){
		//
		this.open_port();
		//prepairing query
		var query = "reset_flashcards_progress=1";
		//sending query to model
		this.xhttp.send(query);
	}
}









//used by the user, tells the model to do something. this class is both a controller and a view
class controller extends model {
	//construct
	constructor(view) {
		super();
		this.view = view;

	}
	//add new spec moduel
	C_minor_ops() {
		var model = this;
		//reset the moduel select (in subject body spec) every page reload
		if (document.getElementById("pt_moduel_var")) {
			if (document.getElementById("pt_moduel_var").value) {
				var subject = document.getElementById("live_subject").value;
				this.C_update_topicField_selection(subject);
			}
		}
		
		
	}
	//
	C_impression_track(){
		//
		var view = this.view;
		var model = this;
		//
		model.M_impression_track();
		
	}
	//
	C_get_impressions_data(){
		//
		var view = this.view;
		var model = this;
		//
		model.M_get_impressions_data();
		//listen for the repsponse from the server script
		this.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				var json = JSON.parse(txt);
				var xs = json.xs;
				var ys = json.ys;
				//
				view.charts_impressions(xs,ys);
			}
		}
	}	
	//
	C_get_performance_data(){
		//
		var view = this.view;
		var model = this;
		//
		model.M_get_performance_data();
		//listen for the repsponse from the server script
		this.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				var json = JSON.parse(txt);
				var xs = json.xs;
				var ys = json.ys;
				//
				view.charts_performance(xs,ys);
			}
		}
	}
	//
	C_make_mindmap(subject,moduel,chapter){
		//
		if (moduel == '' && chapter == '') {
			//
			var root = subject;
		} else if (chapter == '') {
			//
			var root = moduel;
		} else {
			//
			var root = chapter;
		}
		//
		var view = this.view;
		var model = this;

		model.M_make_mindmap(subject,moduel,chapter);
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var node_tree = this.responseText;
				var json = JSON.parse(node_tree);
				//
				var avg = json[json.length -1];
				if(avg['pct']){
					var root2 = avg['pct'] + " % | " + root;
				}else{
					var root2 = root;
				}
				
				json.pop();
				var mind = {
					"meta":{
						"name":"jsMind remote",
						"author":"hizzgdev@163.com",
						"version":"0.2"
					},
					"format":"node_tree",
					"data":{

					"id":"root","topic":root2,"children":json
					}
				};
				var	options = {
				container : 'jsmind_container', 			// [required] ID of the container
				editable : false, 		// Is editing enabled?
				theme : 'asbestos', 		// null is an option theme
				mode :'full', 			// display mode
				support_html : true, 	// Does it support HTML elements in the node?
				view:{
					engine: 'canvas', 	// engine for drawing lines between nodes in the mindmap
					hmargin:100, 		// Minimum horizontal distance of the mindmap from the outer frame of the container
					vmargin:50, 			// Minimum vertical distance of the mindmap from the outer frame of the container
					line_width:2, 		// thickness of the mindmap line
					line_color:'#555' 	// Thought mindmap line color
				},
				layout:{
					hspace:30, 			// horizontal spacing between nodes
					vspace:20, 			// vertical spacing between nodes
					pspace:13 			// Horizontal spacing between node and connection line (to place node expander)
				}
				};
				//$(document).scrollTop( $("#content_aligner").offset().top );
				$("#jsmind_container").empty();
				var jm = new jsMind(options);
				$(document).ready(function () {
					jm.show(mind);
					model.C_draggable_map();
				});
			}
		}
	}
	//
	C_draggable_map(){
		const ele = document.getElementById('jsmind-inner');
		ele.style.cursor = 'grab';
		let pos = { top: 0, left: 0, x: 0, y: 0 };
		const mouseDownHandler = function(e) {
			ele.style.cursor = 'grabbing';
			ele.style.userSelect = 'none';
			pos = {
				left: ele.scrollLeft,
				top: ele.scrollTop,
				// Get the current mouse position
				x: e.clientX,
				y: e.clientY,
			};
			document.addEventListener('mousemove', mouseMoveHandler);
			document.addEventListener('mouseup', mouseUpHandler);
		};
		const mouseMoveHandler = function(e) {
			// How far the mouse has been moved
			const dx = e.clientX - pos.x;
			const dy = e.clientY - pos.y;
			// Scroll the element
			ele.scrollTop = pos.top - dy;
			ele.scrollLeft = pos.left - dx;
		};
		const mouseUpHandler = function() {
			ele.style.cursor = 'grab';
			ele.style.removeProperty('user-select');
			document.removeEventListener('mousemove', mouseMoveHandler);
			document.removeEventListener('mouseup', mouseUpHandler);
		};
		// Attach the handler
		ele.addEventListener('mousedown', mouseDownHandler);
	}
	//
	C_make_mindmap_update(subject,moduel,chapter){
		//
		if (moduel == '' && chapter == '') {
			//
			var root = subject;
			var link = '';
		} else if (chapter == '') {
			//
			var root_num = moduel.substr(0, 2);
			var root_num = parseInt(root_num, 10);
			var root = moduel.replace(/_/g, " ");
			var root = root.substr(2, root.length);
			var root = root.charAt(1).toUpperCase() + root.slice(2);
			var root = root_num +' - '+ root;
			var link = '';
		} else {
			//
			var root_num = chapter.substr(0, 2);
			var root_num = parseInt(root_num, 10);
			var root = chapter.replace(/_/g, " ");
			var root = root.substr(2, root.length);
			var root = root.charAt(1).toUpperCase() + root.slice(2);
			var root = root_num +' - '+ root;
			var holder = '{"a":"redirect","subject":"'+subject+'","moduel":"'+moduel+'","chapter":"'+chapter+'"}';
			var link = JSON.parse(holder);
		}
		//
		var view = this.view;
		var model = this;
		//
		model.M_make_mindmap(subject,moduel,chapter);
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var node_tree = this.responseText;
				var json = JSON.parse(node_tree);
				
				//
				var avg = json[json.length -1];
				if(avg['pct']){
					if(link != ''){
						link['pct'] = avg['pct'];
						link['rgb'] = avg['rgb'];
						var root2 = root;
					}else{
						var root2 = avg['pct'] + " % | " + root;
					}
					
				}else{
					var root2 = root;
				}
				json.pop();
				var mind = {
					"meta":{
						"name":"jsMind remote",
						"author":"hizzgdev@163.com",
						"version":"0.2"
					},
					"format":"node_tree",
					"data":{

					"id":"root","topic":root2,"link":link,"children":json
					}
				};
				var	options = {
				container : 'jsmind_container', 			// [required] ID of the container
				editable : false, 		// Is editing enabled?
				theme : 'asbestos', 			// theme
				mode :'full', 			// display mode
				support_html : true, 	// Does it support HTML elements in the node?
				view:{
					engine: 'canvas', 	// engine for drawing lines between nodes in the mindmap
					hmargin:100, 		// Minimum horizontal distance of the mindmap from the outer frame of the container
					vmargin:50, 			// Minimum vertical distance of the mindmap from the outer frame of the container
					line_width:2, 		// thickness of the mindmap line
					line_color:'#555' 	// Thought mindmap line color
				},
				layout:{
					hspace:30, 			// horizontal spacing between nodes
					vspace:20, 			// vertical spacing between nodes
					pspace:13 			// Horizontal spacing between node and connection line (to place node expander)
				}
				};
				//
				$(document).scrollTop( $("#content_aligner").offset().top );
				$("#jsmind_container").empty();
				var jm = new jsMind(options);
				jm.show(mind);
				model.C_draggable_map();
				
			}
		}
	}
	//
	C_tree_link_click(action,subject,moduel,chapter,topic){
		//
		var view = this.view;
		var model = this;
		//
		if(action == 'refresh'){
			//subject and moduel are always present
			if (chapter == 'undefined' && topic == 'undefined') {
				//refresh using only subject and moduel
				model.C_make_mindmap_update(subject,moduel,'','');
				//refresh the navigation
				
			} else if (topic == 'undefined') {
				//refresh using subject moduel and chapter
				model.C_make_mindmap_update(subject,moduel,chapter,'');
				//refresh the navigation
				
			}
		}else if(action == 'redirect'){
			if(chapter && topic){
				//redirect with topic
				var lo_subject = subject.toLowerCase();
				var link = "http://practicepractice.net/P/Notes/"+lo_subject+"/"+moduel+"/"+chapter+"#to_"+topic
				view.headder_redirect(link);
			}
		}
	}
	//
	C_approve_editorcontract(editor_unique, signature_confirmation) {
		var view = this.view;
		var model = this;
		//
		//
		if (document.getElementById(signature_confirmation).checked) {
			var signature_confirmation = 1;
		} else {
			var signature_confirmation = 0;
		}
		//
		//
		var formdata = new FormData();
		formdata.append('editor_contract_application_approval', 1);
		formdata.append('editor_unique_id', editor_unique);
		formdata.append('signature_confirmation', signature_confirmation);
		//
		var ajax = new XMLHttpRequest();
		//
		ajax.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
		//
		ajax.open("POST", "/I/point_update");
		ajax.send(formdata);
		view.alert_loading();
	}
	//
	C_submit_editorcontract(signature_confirmation) {
		//
		var view = this.view;
		var model = this;
		//
		if (document.getElementById(signature_confirmation).checked) {
			var signature_confirmation = 1;
		} else {
			var signature_confirmation = 0;
		}
		//
		var formdata = new FormData();
		formdata.append('editor_contract_application', 1);
		formdata.append('signature_confirmation', signature_confirmation);
		//
		var ajax = new XMLHttpRequest();
		//
		ajax.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
		//
		ajax.open("POST", "/I/point_update");
		ajax.send(formdata);
		view.alert_loading();
	}
	//
	C_add_newSpec_moduel(subject, moduel) {
		//send command
		var view = this.view
		this.M_add_newSpec_moduel(subject, moduel);

		//make the user wait for the response
		view.alert_loading();
		//listen for the repsponse from the server script
		this.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_update_topicField_selection(subject_in) {
		var subject = subject_in;
		var moduel = document.getElementById('pt_moduel_var').value;
		var element = document.getElementById('pt_chapter_var');
		var view = this.view




		this.M_update_topicField_selection(subject, moduel);
		//		
		this.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//left to input txt into the select element and thats all tdone...
				//also lefr to make this process independent of the users acito of a button click.
				element.innerHTML = txt;
			}
		}


	}
	//
	C_delete_mylist_items() {
		var view = this.view;
		var model = this;

		function process() {
			model.M_delete_mylist_items();
			view.alert_loading();
			model.xhttp.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {
					var out = this.responseText;
					eval(out);
				}
			}

		}
		view.alert_warning_orCancel('Deleting history data !.', process);

	}
	//
	C_Reset_userPassword(pwd_current, pwd_new, pwd_new2) {
		//defining vars
		var pwd_current = document.getElementById(pwd_current).value;
		var pwd_new = document.getElementById(pwd_new).value;
		var pwd_new2 = document.getElementById(pwd_new2).value;
		//defining view and model
		var view = this.view;
		var model = this;
		this.M_Reset_userPassword(pwd_current, pwd_new, pwd_new2);
		view.alert_loading();
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}

	}
	//
	C_update_topicField_selection_question(subject) {
		var subject = subject;
		var moduel = document.getElementById('q_moduel_var').value;
		var element = document.getElementById('q_chapter_var');
		//
		var model = this;
		//
		if(moduel){
			model.M_update_topicField_selection_question(subject, moduel);
			//
			model.xhttp.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {
					var txt = this.responseText;
					//
					var exception = txt.includes("Invalid input");
					//
					if(exception){
						//this is NOT obsolete because of the below else etatement
						eval(txt);
						element.innerHTML = '<option></option>';
					}else{
						//left to input txt into the select element and thats all tdone...
						//also lefr to make this process independent of the users acito of a button click.
						element.innerHTML = txt;
					}
				}
			}
		}else{
			element.innerHTML = '<option></option>';
		}
	}
	//
	C_run_update_refresh(subject){
		//
		var moduel = document.getElementById('q_moduel_var').value;
		//
		if(moduel){
			this.C_update_topicField_selection_question(subject);
		}
	}
	//
	C_update_moduel(pointer) {
		var subject = document.getElementById('subject_input').value;
		var moduel = document.getElementById('moduel_input').value;
		var chapter = document.getElementById('chapter_input').value;
		var new_moduel = document.getElementById(pointer).value;
		//
		var view = this.view;
		var model = this;
		//
		model.M_update_moduel(subject, moduel, new_moduel, chapter);
		view.alert_loading();

		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_update_chapter(pointer, chapter) {
		var subject = document.getElementById('subject_input').value;
		var moduel = document.getElementById('moduel_input').value;
		var new_chapter = document.getElementById(pointer).value
		//
		var view = this.view;
		var model = this;
		//
		model.M_update_chapter(subject, moduel, chapter, new_chapter);
		//loading
		view.alert_loading();
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_add_newSpec_chapter(chapter) {
		var subject = document.getElementById('subject_input').value;
		var moduel = document.getElementById('moduel_input').value;

		var view = this.view;
		var model = this;

		//
		this.M_add_newSpec_chapter(subject, moduel, chapter);
		view.alert_loading();

		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_update_topic(pointer, chapter, topic) {
		var subject = document.getElementById('subject_input').value;
		var moduel = document.getElementById('moduel_input').value;
		var new_topic = document.getElementById(pointer).value;
		//
		var view = this.view;
		var model = this;
		//
		this.M_update_topic(subject, moduel, chapter, topic, new_topic);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_update_topic_editor(pointer, chapter, topic) {
		var subject = document.getElementById('subject_input').value;
		var moduel = document.getElementById('moduel_input').value;
		var new_topic = document.getElementById(pointer).value;
		//
		var view = this.view;
		var model = this;
		//
		this.M_update_topic_editor(subject, moduel, chapter, topic, new_topic);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_add_newSpec_topic(chapter, topic) {
		var subject = document.getElementById('subject_input').value;
		var moduel = document.getElementById('moduel_input').value;
		//
		var view = this.view;
		var model = this;
		//
		this.M_add_newSpec_topic(subject, moduel, chapter, topic);
		view.alert_loading();

		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_add_newSpec_topic_editor(chapter, topic) {
		var subject = document.getElementById('subject_input').value;
		var moduel = document.getElementById('moduel_input').value;
		//
		var view = this.view;
		var model = this;
		//
		this.M_add_newSpec_topic_editor(subject, moduel, chapter, topic);
		view.alert_loading();

		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_update_description(unique_id) {
		var element_1 = "new_input_" + unique_id;
		var description = document.getElementById(element_1).value;
		//
		var view = this.view;
		var model = this;
		//
		model.M_update_description(unique_id, description);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				var json = JSON.parse(txt);
				var element = "DescriptionDisplay_" + unique_id;
				document.getElementById(element).innerHTML = json.description;
				eval(json.alert);
			}
		}
	}
	//
	C_update_description_editor(unique_id) {
		var element_1 = "new_input_" + unique_id;
		var description = document.getElementById(element_1).value;
		//
		var view = this.view;
		var model = this;
		//
		model.M_update_description_editor(unique_id, description);
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				var json = JSON.parse(txt);
				var element = "DescriptionDisplay_" + unique_id;
				document.getElementById(element).innerHTML = json.description;
				eval(json.alert);
			}
		}
	}
	//
	C_spec_page_hide_show(id) {
		var element = document.getElementById(id);
		var style = element.style.display;
		if (style == 'inline') {
			element.style.display = 'none';
		} else if (style == 'none') {
			element.style.display = 'inline';
		}

	}
	//
	C_add_newSpec_point(point_unique_id) {
		//
		var view = this.view;
		var model = this;
		//
		model.M_add_newSpec_point(point_unique_id);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_add_newSpec_point_editor(point_unique_id) {
		//
		var view = this.view;
		var model = this;
		//
		model.M_add_newSpec_point_editor(point_unique_id);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_remove_Spec_point(point_unique_id) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_remove_Spec_point(point_unique_id);
				view.alert_loading();
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel('second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel('Youre deleting a spec point, are you sure ?', process_warning);
	}
	//
	C_remove_Spec_point_editor(point_unique_id) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_remove_Spec_point_editor(point_unique_id);
				view.alert_loading();
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel('second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel('Youre deleting a spec point, are you sure ?', process_warning);
	}
	//
	C_delete_moduel(moduel_unique_id) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_delete_moduel(moduel_unique_id);
				view.alert_loading();
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel('second confirmation, are you suree ?', process);
		}
		view.alert_warning_orCancel('Youre deleting a spec point, are you sure ?', process_warning);
	}
	//
	C_delete_chapter(point_unique) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_delete_chapter(point_unique);
				view.alert_loading();
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel('second confirmation, are you suree ?', process);
		}
		view.alert_warning_orCancel('Youre deleting a spec point, are you sure ?', process_warning);
	}
	//
	C_delete_topic(point_unique) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_delete_topic(point_unique);
				view.alert_loading();
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel('second confirmation, are you suree ?', process);
		}
		view.alert_warning_orCancel('Youre deleting a spec point, are you sure ?', process_warning);
	}
	//
	C_delete_topic_editor(point_unique) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_delete_topic_editor(point_unique);
				view.alert_loading();
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel('second confirmation, are you suree ?', process);
		}
		view.alert_warning_orCancel('Youre deleting a spec point, are you sure ?', process_warning);
	}
	//
	CMVPBroken_uploadFile(point_id) {
		//this didnt work, i had to use the original form
		var view = this.view;
		var model = this;
		//
		var element = 'file1_' + point_id;
		var file = document.getElementById(element).files[0];
		//
		model.M_uploadFile(file, point_id);
		//view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_uploadFile(point_id) {
		//
		var view = this.view;
		var model = this;
		//
		var element = 'file1_' + point_id;
		var file = document.getElementById(element).files[0];
		// eval(file.name+" | "+file.size+" | "+file.type);
		var formdata = new FormData();
		formdata.append('file1', file);
		formdata.append('image_specform_upload', 1);
		formdata.append('pt_id', point_id);
		//
		var ajax = new XMLHttpRequest();
		//
		ajax.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
		//
		ajax.open("POST", "/I/point_update");
		ajax.send(formdata);
		view.alert_loading();

	}
	//
	C_uploadFile_editor(point_id) {
		//
		var view = this.view;
		var model = this;
		//
		var element = 'file1_' + point_id;
		var file = document.getElementById(element).files[0];
		// eval(file.name+" | "+file.size+" | "+file.type);
		var formdata = new FormData();
		formdata.append('editor_updates', 1);
		formdata.append('file1', file);
		formdata.append('image_specform_upload', 1);
		formdata.append('pt_id', point_id);
		//
		var ajax = new XMLHttpRequest();
		//
		ajax.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
		//
		ajax.open("POST", "/I/point_update");
		ajax.send(formdata);
		view.alert_loading();

	}
	//
	C_uploadFile_question(q_unique_id) {
		//
		var view = this.view;
		var model = this;
		//
		var element = 'file1_' + q_unique_id;
		var file = document.getElementById(element).files[0];
		// eval(file.name+" | "+file.size+" | "+file.type);
		var formdata = new FormData();
		formdata.append('file1', file);
		formdata.append('image_question_upload', 1);
		formdata.append('q_unique_id', q_unique_id);
		//
		var ajax = new XMLHttpRequest();
		//
		ajax.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
		//
		ajax.open("POST", "/I/point_update");
		ajax.send(formdata);
		view.alert_loading();

	}
	//
	C_uploadFile_question_editor(q_unique_id) {
		//
		var view = this.view;
		var model = this;
		//
		var element = 'file1_' + q_unique_id;
		var file = document.getElementById(element).files[0];
		// eval(file.name+" | "+file.size+" | "+file.type);
		var formdata = new FormData();
		formdata.append('file1', file);
		formdata.append('image_question_upload_editor', 1);
		formdata.append('q_unique_id', q_unique_id);
		//
		var ajax = new XMLHttpRequest();
		//
		ajax.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
		//
		ajax.open("POST", "/I/point_update");
		ajax.send(formdata);
		view.alert_loading();

	}
	//
	C_delete_file(unique, filename) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			function process() {
				//
				model.M_delete_file(unique, filename);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel('second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel('Youre deleting a spec point, are you sure ?', process_warning);
	}
	//
	C_delete_file_qs(unique, filename) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			function process() {
				//
				model.M_delete_file_qs(unique, filename);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel('second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel('Youre deleting a spec point, are you sure ?', process_warning);
	}
	//
	C_delete_file_editor(unique, filename) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			function process() {
				//
				model.M_delete_file_editor(unique, filename);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel('second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel('Youre deleting a spec point, are you sure ?', process_warning);
	}
	//
	C_lazy_loader() {
		var youtube = document.querySelectorAll(".yvid_youtube");
		var i;
		for (i = 0; i < youtube.length; i++) {

			youtube[i].addEventListener("click", function () {
				var iframe = document.createElement("iframe");
				iframe.setAttribute("frameborder", "0");
				iframe.setAttribute("allowfullscreen", "");
				iframe.setAttribute("accelerometer", "");
				iframe.setAttribute("encrypted-media", "");
				iframe.setAttribute("gyroscope", "");
				iframe.setAttribute("picture-in-picture", "");
				iframe.setAttribute("src", this.dataset.embed);
				this.innerHTML = "";
				this.appendChild(iframe);
			});
		};
	}
	//
	C_toggle_children_visibility(key) {
		var children = document.getElementsByClassName('childof_' + key);
		var wrapper = document.getElementsByClassName('chaptersWrap');
		var index = 0;
		var length = children.length;
		//
		for (; index < length; index++) {
			if (children[index].style.display === "none") {
				children[index].style.display = "block";
			} else {
				children[index].style.display = "none";
			}
		}
		//
		if (wrapper[0].style.display === "none") {
			wrapper[0].style.display = "grid";
		} else {
			wrapper[0].style.display = "none";
		}

	}
	//
	C_delete_html_cache() {
		//
		var view = this.view;
		var model = this;
		//
		function process() {
			//
			model.M_delete_html_cache();
			view.alert_loading();
			//
			model.xhttp.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {
					var out = this.responseText;
					eval(out);
				}
			}
		}
		view.alert_warning_orCancel('Clearing cache', process);
	}
	//
	C_toggle_minispecmenu(number) {
		//
		var number = number;
		var target = 'spec_pointmenu_' + number;
		//
		var element = document.getElementById(target);
		//
		if (element.style.display == 'grid') {
			//visible menu
			element.style.display = 'none';
		} else {
			//hidden menu
			element.style.display = 'grid';
		}

	}
	//
	C_getCookie(name) {
		var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
		return v ? v[2] : null;
	}
	//
	C_toggle_css() {
		var con = this;
		var usr_cookie = con.C_getCookie('css_mode');
		var sheet = document.getElementById('darkmode_css');
		//give cookie if none is set
		if (!usr_cookie) {
			document.cookie = 'css_mode=light;path=/';
		}




		//remember ** causes page double reload
		if (usr_cookie == 'dark') {
		//	$('#darkmode_css').attr('href', '/css/dark_mode.css');
		} else if (usr_cookie == 'yellow') {
		//	$('#darkmode_css').attr('href', '/css/yellow_mode.css');
		} else {
		//	$('#darkmode_css').attr('href', '#');
		}
		//when the user click
		$('#style_togg_ul').click(function () {
			//get his saved cookie
			var usr_cookie_live = con.C_getCookie('css_mode');
			//
			if (usr_cookie_live == 'light') {
				$('#darkmode_css').attr('href', '/css/dark_mode.css');
				document.cookie = 'css_mode=dark;path=/';
			} else if (usr_cookie_live == 'dark') {
				$('#darkmode_css').attr('href', '/css/yellow_mode.css');
				document.cookie = 'css_mode=yellow;path=/';
			} else {
				if (usr_cookie_live == 'yellow') {
					$('#darkmode_css').attr('href', '#');
					document.cookie = 'css_mode=light;path=/';
				}
			}
		});


		$(document).ready(function () {
			$('body').attr('style', 'display:block;');
		});
	}
	//
	C_specpoint_DBprocessing() {
		//
		var view = this.view;
		var model = this;
		//
		model.M_specpoint_DBprocessing();
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_question_DBprocessing() {
		//
		var view = this.view;
		var model = this;
		//
		model.M_question_DBprocessing();
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_paper_DBprocessing() {
		//
		var view = this.view;
		var model = this;
		//
		model.M_paper_DBprocessing();
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_user_login() {
		//
		var view = this.view;
		var model = this;
		//
		var uid = document.getElementById('login_uid').value;
		var pwd = document.getElementById('login_pwd').value;
		//
		model.M_user_login(uid, pwd);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_user_logoff() {
		//
		var view = this.view;
		var model = this;
		//
		//
		model.M_user_logoff();
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_user_recover_password() {
		//
		var view = this.view;
		var model = this;
		//
		var account_email = document.getElementById('user_pwdrecovery_email').value;
		//
		model.M_user_recover_password(account_email);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_register_new_pwd() {
		//
		var view = this.view;
		var model = this;
		//
		var vkey = document.getElementById('new_pwd_vkey').value;
		var pwd1 = document.getElementById('new_pwd_password1').value;
		var pwd2 = document.getElementById('new_pwd_password2').value;
		//
		model.M_register_new_pwd(vkey, pwd1, pwd2);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_user_newsignup() {
		//
		var view = this.view;
		var model = this;
		//
		var username = document.getElementById('signup_username').value;
		var firstname = document.getElementById('signup_firstname').value;
		var lastname = document.getElementById('signup_lastname').value;
		var email1 = document.getElementById('signup_email1').value;
		var email2 = document.getElementById('signup_email2').value;
		var pwd1 = document.getElementById('signup_pwd1').value;
		var pwd2 = document.getElementById('signup_pwd2').value;
		//
		model.M_user_newsignup(username, firstname, lastname, email1, email2, pwd1, pwd2);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_verify_newuser(key) {
		//
		var view = this.view;
		var model = this;
		//
		model.M_verify_newuser(key);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}	
	//
	C_user_list_specfilter() {
		//
		var view = this.view;
		var model = this;
		//
		var user_unique_id = document.getElementById('user_list_unique').value;
		var subject = document.getElementById('pt_subject').value;
		//
		model.M_user_list_specfilter(subject, user_unique_id);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_update_raw_spec_info(subject_holder, moduel_holder, chapter_holder, pt_information_holder) {
		var view = this.view;
		var model = this;
		//
		var subject = document.getElementById(subject_holder).value;
		var moduel = document.getElementById(moduel_holder).value;
		var chapter = document.getElementById(chapter_holder).value;
		var pt_information = document.getElementById(pt_information_holder).value;
		//
		model.M_update_raw_spec_info(subject, moduel, chapter, pt_information);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_AssignEditor(subject, moduel, chapter,amount) {
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				var value = document.getElementById(amount).value;
				model.M_AssignEditor(subject, moduel, chapter, value);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						//
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be assiging work, are you sure ?', process_warning);
	}
	//
	C_hireuser(user_unique_id, Email, EditorPrimary_subject, EditorSecondary_subject) {
		var view = this.view;
		var model = this;
		//
		var user_unique = document.getElementById(user_unique_id).value;
		var email = document.getElementById(Email).value;
		var primary_subject = document.getElementById(EditorPrimary_subject).value;
		var secondary_subject = document.getElementById(EditorSecondary_subject).value;
		//
		model.M_hireuser(user_unique, email, primary_subject, secondary_subject);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_editor_cancel_contract(editor_unique_id) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				model.M_editor_cancel_contract(editor_unique_id);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						//
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('you are cancelling a contract, are you sure ?', process_warning);



	}
	//
	C_editor_suspension(editor_unique_id) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				model.M_editor_suspension(editor_unique_id);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						//
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You are suspending an editor, are you sure ?', process_warning);





	}
	//
	C_editor_claimtask(editor_unique_id, pt_subject, pt_moduel, pt_chapter) {
		//
		var view = this.view;
		var model = this;
		//
		model.M_editor_claimtask(editor_unique_id, pt_subject, pt_moduel, pt_chapter);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_editor_droptask(editor_unique_id, pt_subject, pt_moduel, pt_chapter) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_editor_droptask(editor_unique_id, pt_subject, pt_moduel, pt_chapter);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						//
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will delete everything you have written, are you sure ?', process_warning);
	}
	//
	C_editor_droptask_question(user_unique_id, q_subject, q_moduel, q_chapter, q_topic, q_point) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_editor_droptask_question(user_unique_id, q_subject, q_moduel, q_chapter, q_topic, q_point);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						//
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will delete everything you have written, are you sure ?', process_warning);
	}
	//
	C_unlist_editor_task(pt_subject, pt_moduel, pt_chapter) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_unlist_editor_task(pt_subject, pt_moduel, pt_chapter);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						//
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be unlisting a task, are you sure ?', process_warning);
	}
	//
	C_letEditor_work(editor_unique_id, pt_subject, pt_moduel, pt_chapter) {
		//
		var view = this.view;
		var model = this;
		//
		model.M_letEditor_work(editor_unique_id, pt_subject, pt_moduel, pt_chapter);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_editor_submit_work(editor_unique_id, pt_subject, pt_moduel, pt_chapter) {
		//
		var view = this.view;
		var model = this;
		//
		
		function process_warning() {
			//
			function process() {
				//
				model.M_editor_submit_work(editor_unique_id, pt_subject, pt_moduel, pt_chapter);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						//
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be submitting for review, are you sure ?', process_warning);
	}
	//
	C_submit_work_question(subject, moduel, chapter, topic, point_unique) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_submit_work_question(subject, moduel, chapter, topic, point_unique);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be submitting for review, are you sure ?', process_warning);
	}
	//
	C_editor_submit_work_question(editor_unique_id, pt_subject, pt_moduel, pt_chapter) {
		//
		var view = this.view;
		var model = this;
		//
		
		function process_warning() {
			//
			function process() {
				//
				model.M_editor_submit_work(editor_unique_id, pt_subject, pt_moduel, pt_chapter);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						//
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be submitting for review, are you sure ?', process_warning);
		
	}
	//
	C_admin_review_redirect(editor_unique_id, pt_subject, pt_moduel, pt_chapter) {
		//
		var view = this.view;
		var model = this;
		//
		model.M_admin_review_redirect(editor_unique_id, pt_subject, pt_moduel, pt_chapter);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_admin_review_redirect_question(editor_unique, q_subject, q_moduel, q_chapter, q_topic, q_point) {
		//
		var view = this.view;
		var model = this;
		//
		model.M_admin_review_redirect_question(editor_unique, q_subject, q_moduel, q_chapter, q_topic, q_point);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_admin_accept_work(editor_unique_id, pt_subject, pt_moduel, pt_chapter) {
		//
		var view = this.view;
		var model = this;
		//
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_admin_accept_work(editor_unique_id, pt_subject, pt_moduel, pt_chapter);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						//
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be accepting work, are you sure ?', process_warning);


	}
	//
	C_accept_work_question(editor_unique_id, q_subject, q_moduel, q_chapter, q_topic, q_point) {
		//
		var view = this.view;
		var model = this;
		//
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_accept_work_question(editor_unique_id, q_subject, q_moduel, q_chapter, q_topic, q_point);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						//
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be accepting work, are you sure ?', process_warning);


	}
	//
	C_admin_reject_work(editor_unique_id, pt_subject, pt_moduel, pt_chapter) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_admin_reject_work(editor_unique_id, pt_subject, pt_moduel, pt_chapter);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						//
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be rejecting work, are you sure ?', process_warning);
	}
	//
	C_reject_work_question(editor_unique, subject, moduel, chapter, topic, point_unique) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_reject_work_question(editor_unique, subject, moduel, chapter, topic, point_unique);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						//
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be rejecting work, are you sure ?', process_warning);
	}
	//
	C_see_questions(filter_type, pt_unique_id) {
		var view = this.view;
		var model = this;
		//
		model.M_see_questions(filter_type, pt_unique_id);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_create_new_question(filter, subject, moduel, chapter, topic, point_unique) {
		//
		var view = this.view;
		var model = this;
		//
		model.M_create_new_question(filter, subject, moduel, chapter, topic, point_unique);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_create_new_question_editor(filter, subject, moduel, chapter, topic, point_unique) {
		//
		var view = this.view;
		var model = this;
		//
		model.M_create_new_question_editor(filter, subject, moduel, chapter, topic, point_unique);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_update_question_info(question_unique) {
		//
		var element_1 = "full_question_" + question_unique;
		var description = document.getElementById(element_1).value;
		//
		var view = this.view;
		var model = this;
		//
		model.M_update_question_info(question_unique, description);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_update_question_info_editor(question_unique) {
		//
		var element_1 = "full_question_" + question_unique;
		var description = document.getElementById(element_1).value;
		//
		var view = this.view;
		var model = this;
		//
		model.M_update_question_info_editor(question_unique, description);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_delete_question(question_unique) {
		//
		var view = this.view;
		var model = this;


		//
		function process_warning() {
			//
			function process() {
				//
				model.M_delete_question(question_unique);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be deleting this question, are you sure ?', process_warning);
	}
	//
	C_delete_question_editor(question_unique) {
		//
		var view = this.view;
		var model = this;


		//
		function process_warning() {
			//
			function process() {
				//
				model.M_delete_question_editor(question_unique);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be deleting this question, are you sure ?', process_warning);
	}
	//
	C_toggle_answer(locator) {
		//
		var target = 'loc_' + locator;
		//
		var element = document.getElementById(target);
		//
		if (element.style.display == 'block') {
			//visible menu
			element.style.display = 'none';
		} else {
			//hidden menu
			element.style.display = 'block';
		}
	}
	//
	C_toggle_fp_answer(locator) {
		//
		var target = locator;
		//
		var element = document.getElementById(target);
		//
		if (element.style.visibility == 'visible') {
			//visible menu
			element.style.visibility = 'hidden';
		} else {
			//hidden menu
			element.style.visibility = 'visible';
		}
	}
	//
	C_toggle_fp_grid_on(locator) {
		//
		var target = locator;
		//
		var element = document.getElementById(target);
		//
		if (element.style.display == 'grid') {
			//visible menu
			//element.style.display = 'none';
		} else {
			//hidden menu
			element.style.display = 'grid';
		}
	}
	//
	C_toggle_fp_grid_off(locator) {
		//
		var target = locator;
		//
		var element = document.getElementById(target);
		//
		if (element.style.display == 'grid') {
			//visible menu
			element.style.display = 'none';
		} else {
			//hidden menu
			//element.style.display = 'grid';
		}
	}
	//
	C_mark_fc(status,id,list_id){
		//
		var view = this.view;
		var model = this;
		var nxt = parseInt(list_id) + 1;
		var current = "view_fc_"+list_id;
		var next = "view_fc_"+nxt;
		//
		model.M_mark_fc(status,id);
		
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				if(txt == 1){
					//
					if(document.body.contains(document.getElementById(next))){
						model.C_toggle_fp_grid_off(current);
						model.C_toggle_fp_grid_on(next);
					}else{
						model.C_toggle_fp_grid_off(current);
						view.alert_popup_info("You've completed all due flashcards.");
					}
				}else{
					view.alert_error('Something went wrong');
				}
				
			}
		}
		
	}
	//
	C_reset_flashcards(){
		//
		var view = this.view;
		var model = this;
		//
		model.M_reset_flashcards();
		view.alert_loading();
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_toggle_fm_item(locator) {
		//
		var children = document.getElementsByClassName(locator);
		var index = 0;
		var length = children.length;
		//
		for (; index < length; index++) {
			if (children[index].style.display === "none") {
				children[index].style.display = "block";
			} else {
				children[index].style.display = "none";
			}
		}
	}
	//
	C_assign_editor_question_section(filter, q_subject, q_moduel, q_chapter, q_topic, q_point,amount) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				var value = document.getElementById(amount).value;
				model.M_assign_editor_question_section(filter, q_subject, q_moduel, q_chapter, q_topic, q_point,value);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be assigning an editor, are you sure ?', process_warning);
	}
	//
	C_unlist_editor_task_question(subject, moduel, chapter, topic, point) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_unlist_editor_task_question(subject, moduel, chapter, topic, point);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be unlisting a task, are you sure ?', process_warning);
	}
	//
	C_editor_claimtask_question(user_unique, q_subject, q_moduel, q_chapter, q_topic, q_point) {
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_editor_claimtask_question(user_unique, q_subject, q_moduel, q_chapter, q_topic, q_point);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be claiming a task, are you sure ?', process_warning);
	}
	//
	C_letEditor_work_question(user_unique, q_subject, q_moduel, q_chapter, q_topic, q_point) {
		//
		var view = this.view;
		var model = this;
		//
		model.M_letEditor_work_question(user_unique, q_subject, q_moduel, q_chapter, q_topic, q_point);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_initiate_membership_payment(){
		//
		var view = this.view;
		var model = this;
		//
		var membership_option = document.getElementById('payment_subtotal').value;
		//
		model.M_initiate_membership_payment(membership_option);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_finalise_membership_payment(token,payer_id){
		//
		var view = this.view;
		var model = this;
		//
		model.M_finalise_membership_payment(token,payer_id);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_cancel_membership_payment(){
		//
		var view = this.view;
		var model = this;
		//
		model.M_cancel_membership_payment();
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_initiate_editor_payout(user_unique, pt_subject, pt_moduel, pt_chapter){
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_initiate_editor_payout(user_unique, pt_subject, pt_moduel, pt_chapter);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be paying editors, are you sure ?', process_warning);
	
	}
	//
	C_initiate_editor_payout_bnkak(user_unique, pt_subject, pt_moduel, pt_chapter){
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_initiate_editor_payout_bnkak(user_unique, pt_subject, pt_moduel, pt_chapter);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be paying editors, are you sure ?', process_warning);
	
	}	
	//
	C_initiate_editor_payout_question(user_unique, q_subject, q_moduel, q_chapter, q_topic, q_point){
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_initiate_editor_payout_question(user_unique, q_subject, q_moduel, q_chapter, q_topic, q_point);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be paying editors, are you sure ?', process_warning);
	
	}
	//
	C_initiate_editor_payout_question_bnkak(user_unique, q_subject, q_moduel, q_chapter, q_topic, q_point){
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_initiate_editor_payout_question_bnkak(user_unique, q_subject, q_moduel, q_chapter, q_topic, q_point);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be paying editors, are you sure ?', process_warning);
	
	}
	//
	C_editor_bnkakaccount_input(input){
		//
		var editor_bnkak_id = document.getElementById(input).value;
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_editor_bnkakaccount_input(editor_bnkak_id);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure it is correct ?', process);
		}
		view.alert_warning_orCancel_go('You will be submitting your Bnkak ID, make sure it is correct', process_warning);
		
	}
	//
	C_editor_paypalemail_input(input){
		//
		var editor_paypal_id = document.getElementById(input).value;
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_editor_paypalemail_input(editor_paypal_id);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be submitting your paypal ID, are you sure ?', process_warning);
		
	}
	//
	C_editor_paypalverification_input(input){
		//
		var editor_paypalverification_code = document.getElementById(input).value;
		//
		var view = this.view;
		var model = this;
		//
		model.M_editor_paypalverification_input(editor_paypalverification_code);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_alert_editor_work(){
		//
		var view = this.view;
		var model = this;
		//
		model.M_alert_editor_work();
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_publish_moduel(subject,moduel){
		//
		var view = this.view;
		var model = this;
		//
		model.M_publish_moduel(subject,moduel);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_unpublish_moduel(subject,moduel){
		//
		var view = this.view;
		var model = this;
		//
		model.M_unpublish_moduel(subject,moduel);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
			}
		}
	}
	//
	C_delete_cache(){
		//
		var view = this.view;
		var model = this;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_delete_cache();
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be deleting this question, are you sure ?', process_warning);
	}
	//
	C_contact_form(name,email,subject,message){
		var view = this.view;
		var model = this;
		//
		var name = document.getElementById(name).value;
		var to_email = document.getElementById(email).value;
		var subject = document.getElementById(subject).value;
		var message = document.getElementById(message).value;
		//
		model.M_contact_form(name, to_email, subject, message);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);
			}
		}
	}
	//
	C_get_custom_practice(subject,moduel_el,chapter_el,type_el,diff_el){
		var view = this.view;
		var model = this;
		//
		var subject = subject;
		var moduel = document.getElementById(moduel_el).value;
		var chapter = document.getElementById(chapter_el).value;
		var type = document.getElementById(type_el).value;
		var diff = document.getElementById(diff_el).value;
		//
		model.M_get_custom_practice(subject,moduel,chapter,type,diff);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				var exception = txt.includes("Invalid input");
				//
				if(exception){
					//
					eval(txt);
				}else{
					//
					var link = "http://practicepractice.net/P/customquestions/"+subject+"/"+moduel+"/"+chapter+"/"+type+"/"+diff+'/1';
					view.headder_redirect(link);
				}
				//
				
			}
		}
		
	}
	//
	C_mark_question(q_unique_id,total){
		var view = this.view;
		var model = this;
		//
		var q_unique_id = q_unique_id;
		var mark = document.getElementById('q_'+q_unique_id).value;
		//
		model.M_mark_question(q_unique_id,mark,total);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);	
			}
		}
	}
	//
	C_toggle_question_visibility(user_unique_id){
		//
		var view = this.view;
		var model = this;
		//
		model.M_toggle_question_visibility(user_unique_id);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);	
			}
		}
	}	
	//
	C_toggle_level(user_unique_id){
		//
		var view = this.view;
		var model = this;
		//
		model.M_toggle_level(user_unique_id);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);	
			}
		}
	}
	//
	C_locate_question(question_unique){
		//
		//
		var view = this.view;
		var model = this;
		//
		model.M_locate_question(question_unique);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);	
			}
		}
	}
	//
	C_direct_to_pastpaper(subject,q_origin){
		//
		var q_origin = document.getElementById(q_origin).value;
		//
		var view = this.view;
		var link = "http://practicepractice.net/P/pastpapers/"+subject+"/"+q_origin;
		view.headder_redirect(link);
		
	}
	//
	C_make_progress_bar(subject,moduel,chapter,topic){
		//
		var view = this.view;
		var model = this;
		//
		model.M_make_progress_bar(subject,moduel,chapter,topic);
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				var json = JSON.parse(txt);
				var pct = json.pct;
				var rgb = json.rgb;
				//
				var i = 0;
				if (i == 0) {
					i = 1;
					var container = document.getElementById("myProgress");
					var elem = document.getElementById("myBar");
					var elem_val = document.getElementById("myBar_val");
					elem.style.background = rgb;
					var width = 0;
					var id = setInterval(frame, 15);
					function frame() {
					  if (width >= pct) {
						clearInterval(id);
						i = 0;
					  } else {
						width++;
						
						elem.style.width = width + "%";
						elem_val.innerHTML = width  + "%";
					  }
					}
				}	
			}
		}
	}
	//
	C_advance_bar(A,B,C,pct,rgb){
		//
		var i = 0;
		if (i == 0) {
			i = 1;
			var container = document.getElementById(A);
			var elem = document.getElementById(B);
			var elem_val = document.getElementById(C);
			elem.style.background = rgb;
			var width = 0;
			var id = setInterval(frame, 15);
			function frame() {
			  if (width >= pct) {
				clearInterval(id);
				i = 0;
			  } else {
				width++;

				elem.style.width = width + "%";
				elem_val.innerHTML = width  + "%";
			  }
			}
		}
	}
	//
	C_plan_disp(type, price){
		var elem = document.getElementById("payment_disp");
		//
		var month = document.getElementById("month_button");
		var sixmonth = document.getElementById("sixmonth_button");
		var ninemonth = document.getElementById("ninemonth_button");
		var price_disp = document.getElementById("price_display");
		//
		if(document.getElementById("payment_subtotal") &&  document.getElementById("payment_subtotal") &&  document.getElementById("payment_subtotal")){
			var subtotal = document.getElementById("payment_subtotal");
			var discount = document.getElementById("payment_discount");
			var total = document.getElementById("payment_total");
		}
		var new_price = "£"+price+" /m";	
		price_disp.innerHTML= new_price;
		//
		if(type == 1){
			month.style = 'background:green;color:white;';
			sixmonth.style= '';
			ninemonth.style = ''; 
			//
			if(document.getElementById("payment_subtotal") &&  document.getElementById("payment_subtotal") &&  document.getElementById("payment_subtotal")){
				subtotal.setAttribute('value', 1)
				subtotal.innerHTML = "Subtotal: £" + Math.round(price* 100) / 100;
				
			}
		}else if(type ==6){
			month.style = '';
			sixmonth.style= 'background:green;color:white;';
			ninemonth.style = ''; 
			//
			if(document.getElementById("payment_subtotal") &&  document.getElementById("payment_subtotal") &&  document.getElementById("payment_subtotal")){
				subtotal.setAttribute('value', 2)
				subtotal.innerHTML = "Subtotal: £" + Math.round(price*6* 100) / 100;
				
			}
		}else{
			if(type == 9){
				month.style = '';
				sixmonth.style= '';
				ninemonth.style = 'background:green;color:white;'; 
				//
				if(document.getElementById("payment_subtotal") &&  document.getElementById("payment_subtotal") &&  document.getElementById("payment_subtotal")){
					subtotal.setAttribute('value', 3)
					subtotal.innerHTML = "Subtotal: £" + Math.round(price*9* 100) / 100;
					
				}
			}else{
				elem.innerHTML = html;
				//
				if(document.getElementById("payment_subtotal") &&  document.getElementById("payment_subtotal") &&  document.getElementById("payment_subtotal")){
					subtotal.setAttribute('value', 1)
					subtotal.innerHTML = "Subtotal: £";
					
				}
			}
		}
	}
	//
	C_apply_discount_code(){
		var code =  document.getElementById("membership_discount_input").value;
		//
		var view = this.view;
		var model = this;
		//
		model.M_apply_discount_code(code);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				eval(txt);
				model.C_plan_disp(type);
			}
		}
		
	}
	//
	C_make_window_elm(id,value,txt,class_n,parent_indic = null){
		//
		if(document.getElementById('disp_'+ id)){
			var checked = 'checked';
		}else{
			var checked = '';
		}
		var a = "<div class = 'maker_windw_option "+parent_indic+"' data-value='"+value+"'>";
		var b = "<input type='checkbox' id='"+id+ "' onclick=controller.C_add_maker_option('"+id+"','"+encodeURI(txt)+"','"+class_n+"','"+parent_indic+"') "+checked+" />";
		var c = "<p>"+txt+"</p>";
		var d = "</div>";
		return a+b+c+d;
	}
	//
	C_make_display_elm(id,value,txt,class_n,parent_indic){
		if(class_n == 'cq_chapter'){
			var parent_moduel = 'Parent_'
		}
		var a = "<div class ='maker_display_option "+class_n+' '+parent_indic+"' id ='disp_"+id+"' data-value='"+value+"'> ";
		var b = "<p style='display: inline-block;'>"+txt+"</p>";
		var c = "<a style='display: inline-block;' onclick =controller.C_remove_display_elm('"+id+"')>X</a>";
		var d = "</div>";
		return a+b+c+d;
	}
	//
	C_remove_display_elm(id){
		var checkbox = document.getElementById(id);
		var elm = document.getElementById('disp_'+ id);
		elm.remove();
		if(checkbox){
			checkbox.checked = false;
		}
		this.C_remove_children(id);
	}
	//
	C_remove_children(id){
		//
		var view = this.view;
		var model = this;
		//
		var children = document.getElementsByClassName("Parent_"+id);
		//
		if(children.length > 0){
			while(children[0]) {
				var value = children[0].getAttribute('data-value');
				var elm = document.getElementById('disp_'+value);
				if(elm){
					elm.remove();
				}
				if(children[0]){
					children[0].remove();
				}
				
			}	
		}	
	}
	//
	C_maker_catagories(tab,current_tab){
		//
		var view = this.view;
		var model = this;
		//
		var tab_elm = document.getElementById(current_tab);
		var elems = document.getElementsByClassName("maker_tabs");
		for (var i = 0; i < elems.length; i ++) {
				elems[i].style.background = 'none';
				elems[i].style.color = 'black';
		}
		tab_elm.style.background = 'grey';
		tab_elm.style.color = 'white';
		//
		//
		if(tab == 'levels'){
			var elem = document.getElementById("maker_display");
			var html = '';
			var html = html + this.C_make_window_elm('C1','C1','C1','cq_level');
			var html = html + this.C_make_window_elm('C2','C2','C2','cq_level');
			var html = html + this.C_make_window_elm('C12','C12','C12','cq_level');
			var html = html + this.C_make_window_elm('C3','C3','C3','cq_level');
			var html = html + this.C_make_window_elm('C4','C4','C4','cq_level');
			var html = html + this.C_make_window_elm('C34','C34','C34','cq_level');
			var html = html + this.C_make_window_elm('M1','M1','M1','cq_level');
			var html = html + this.C_make_window_elm('S1','S1','S1','cq_level');
			elem.innerHTML = html;
		}if(tab == 'type'){
			var elem = document.getElementById("maker_display");
			var html = '';
			var html = html +  this.C_make_window_elm('type_short','S','Short','cq_type');
			var html = html +  this.C_make_window_elm('type_medium','M','Medium','cq_type');
			var html = html +  this.C_make_window_elm('type_long','L','long','cq_type');
			elem.innerHTML = html;
		}if(tab == 'difficulty'){
			var elem = document.getElementById("maker_display");
			var html = '';
			var html = html +  this.C_make_window_elm('diff_1','1','1','cq_difficulty');
			var html = html +  this.C_make_window_elm('diff_2','2','2','cq_difficulty');
			var html = html +  this.C_make_window_elm('diff_3','3','3','cq_difficulty');
			var html = html +  this.C_make_window_elm('diff_4','4','4','cq_difficulty');
			var html = html +  this.C_make_window_elm('diff_5','5','5','cq_difficulty');
			elem.innerHTML = html;
		}if(tab == 'pastpapers'){
			var elem = document.getElementById("maker_display");
			var html = '';
			var html = html +  this.C_make_window_elm('pastpapers_opt','1','Use past papers','cq_is_exam');
			elem.innerHTML = html;
		}
	}
	//
	C_maker_catagories_moduels(moduels,current_tab){
		//
		var view = this.view;
		var model = this;
		//
		var tab_elm = document.getElementById(current_tab);
		var elems = document.getElementsByClassName("maker_tabs");
		for (var i = 0; i < elems.length; i ++) {
				elems[i].style.background = 'none';
				elems[i].style.color = 'black';
		}
		tab_elm.style.background = 'grey';
		tab_elm.style.color = 'white';
		//moduel => value,display
		var json = JSON.parse(moduels);
		
		var elem = document.getElementById("maker_display");
		//
		var html = '';
		var i = 0;
		var keys = Object.keys(json);
		for (i =0; i<keys.length; i++) {
			var value = json[i]['value'];
			var display = json[i]['display'];
			var html = html + model.C_make_window_elm(value,value,display,'cq_moduel');
		}
		elem.innerHTML = html;
	}
	//
	C_maker_catagories_chapters(subject,current_tab){
		//
		var view = this.view;
		var model = this;
		//
		var tab_elm = document.getElementById(current_tab);
		var elems = document.getElementsByClassName("maker_tabs");
		for (var i = 0; i < elems.length; i ++) {
				elems[i].style.background = 'none';
				elems[i].style.color = 'black';
		}
		tab_elm.style.background = 'grey';
		tab_elm.style.color = 'white';
		//
		var moduels = document.getElementsByClassName("cq_moduel");
		if(moduels.length > 0){
			var arr_mods_json =model.C_fetch_class_items('cq_moduel');

			var formdata = new FormData();
			formdata.append('paper_maker_get_chapters', 1);
			formdata.append('q_subject', subject);
			formdata.append('q_moduel_array', encodeURIComponent(arr_mods_json));

			//
			var ajax = new XMLHttpRequest();
			//
			ajax.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {
					var txt = this.responseText;
					//
					if(txt != 0){
						//
						var arr = JSON.parse(txt);
						var elem = document.getElementById("maker_display");
						var html = '';
						for(var i = 0; i < arr.length; i ++) {
							var chapter = arr[i];
							var c_val = arr[i]['value'];
							var c_disp = arr[i]['disp'];
							var rel_moduel = arr[i]['rel_moduel'];
							var parent_indic = 'Parent_'+rel_moduel;
							var html = html + model.C_make_window_elm(c_val,c_val,c_disp,"cq_chapter",parent_indic);
						}
						//
						setTimeout(function(){ elem.innerHTML = html; }, 1000);
						//
					}else{
						view.alert_warning('Something went wrong: Check your moduel selection');
					}
				}
			}
			//
			ajax.open("POST", "/I/point_update");
			ajax.send(formdata);
			var elem = document.getElementById("maker_display");
			elem.innerHTML = "<div class='loader'></div>";
		}else{
			var elem = document.getElementById("maker_display");
			elem.innerHTML = "<a style ='color:red;'>Moduel selection is required";
		}
	}
	//
	C_add_maker_option(checkbox_id,display,class_n,parent_indic){
		//
		var view = this.view;
		var model = this;
		//
		var checkbox = document.getElementById(checkbox_id);
		var value = checkbox.parentElement.getAttribute('data-value');
		var elem = document.getElementById("maker_selections");
		
		if(checkbox.checked){
			var html = this.C_make_display_elm(checkbox_id,value,decodeURI(display),class_n,parent_indic);
			var save = elem.innerHTML;
			elem.innerHTML = elem.innerHTML + html;
		}else{
			var removed_elm = 'disp_'+checkbox_id;
			var removed_elm = document.getElementById(removed_elm);
			this.C_remove_children(checkbox_id)
			removed_elm.remove();
		}
		
		
	}
	//
	C_fetch_class_items(classname){
		var elements = document.getElementsByClassName(classname);
		if(elements.length > 0){
			var arr_mods = new Array();
			for (var i = 0; i < elements.length; i ++) {
				arr_mods[i] = elements[i].getAttribute('data-value');
			}
			return JSON.stringify(arr_mods);
		}else{
			return 0;
		}
	}
	//
	C_get_practice_paper(){
		//
		var view = this.view;
		var model = this;
		//
		var q_level = model.C_fetch_class_items('cq_level');
		var q_subject = model.C_fetch_class_items('cq_subject');
		var q_moduel = model.C_fetch_class_items('cq_moduel');
		var q_chapter = model.C_fetch_class_items('cq_chapter');
		var q_type = model.C_fetch_class_items('cq_type');
		var q_difficulty = model.C_fetch_class_items('cq_difficulty');
		var q_is_exam = model.C_fetch_class_items('cq_is_exam');
		//
		var formdata = new FormData();
		formdata.append('get_paper_papermaker', 1);
		formdata.append('q_level_array', encodeURIComponent(q_level));
		formdata.append('q_subject_array', encodeURIComponent(q_subject));
		formdata.append('q_moduel_array', encodeURIComponent(q_moduel) );
		formdata.append('q_chapter_array', encodeURIComponent(q_chapter));
		formdata.append('q_type_array', encodeURIComponent(q_type));
		formdata.append('q_difficulty_array', encodeURIComponent(q_difficulty));
		formdata.append('q_is_exam_array', encodeURIComponent(q_is_exam));
		//
		var ajax = new XMLHttpRequest();
		//
		ajax.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				if(txt != 0){
					view.headder_redirect("https://www.practicepractice.net/"+txt);
				}else{
					view.alert_warning('Something went wrong: cannot create paper, add more options and make sure to allow for pastpapers');
				}
				
			}
		}
		//
		ajax.open("POST", "/I/point_update");
		ajax.send(formdata);
		view.alert_loading();
	}
	//
	C_make_print_userpaper(user_unique,paper_unique){
		//
		var view = this.view;
		//
		view.headder_redirect("https://www.practicepractice.net/P/print_user_paper/"+user_unique+'/'+paper_unique);
	}
	//
	C_organise_paper(){
		//
		var model = this;
		var view = this.view;
		//
		var paper_name = document.getElementById('paper_name').value;
		//
		model.M_organise_paper(paper_name);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);	
			}
		}
	}
	//
	C_relocate_question_files(q_unique_id){
		//
		var model = this;
		var view = this.view;
		//
		var pt_unique_id = document.getElementById('reloc_'+q_unique_id).value;
		//
		model.M_relocate_question_files(q_unique_id,pt_unique_id);
		view.alert_loading();
		//
		model.xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var txt = this.responseText;
				//
				eval(txt);	
			}
		}
	}
	//
	C_relocate_question_files2(q_unique_id,pt_unique_id){
		//
		var model = this;
		var view = this.view;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_relocate_question_files2(q_unique_id, pt_unique_id);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						//
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be relocating this question, are you sure ?', process_warning);
		
	}
	//
	C_affiliate_payout(){
		//
		var model = this;
		var view = this.view;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_affiliate_payout();
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be deleting this question, are you sure ?', process_warning);
	}
	//
	async C_create_flashcard(pt_unique_id){
		//
		var model = this;
		var view = this.view;
		//
		const { value: formValues } = await Swal.fire({
		title: 'Flashcard',
		showCancelButton: true,
		cancelButtonText: 'Cancel',
		html:
			'<textarea id="swal-input1" class="swal2-input" placeholder="Front: Your question"></textarea>' +
			'<textarea id="swal-input2" class="swal2-input" placeholder="Back: Your answer"></textarea>',
		focusConfirm: false,
		preConfirm: () => {
		return [
		  document.getElementById('swal-input1').value,
		  document.getElementById('swal-input2').value
		]
		}
		});
		//
		if (formValues){
			if (formValues[0] && formValues[1]) {
				
				setTimeout(function(){MathJax.typeset() }, 500);
				//
				const { value: Confirmation } = await Swal.fire({
				title: 'Review',
				showCancelButton: true,
				cancelButtonText: 'Cancel',
				html:
					'<div id="temp_mathjax"><p style="border-bottom:1px solid black;">Front: '+formValues[0]+'</p>' +
					'<p>Back: '+formValues[1]+'</p></div>',
					
				focusConfirm: false,
				preConfirm: () => {return [1]}
				});
				//
				if (Confirmation) {
					var question = formValues[0];
					var answer = formValues[1];
					//
					model.M_create_flashcard(pt_unique_id,question,answer);
					view.alert_loading();
					//
					model.xhttp.onreadystatechange = function () {
						if (this.readyState == 4 && this.status == 200) {
							var txt = this.responseText;
							eval(txt);
						}
					}
				}else{
					view.alert_error("Nothing was done");
				}
			}else{
				view.alert_error("Nothing was done");
			}
		}else{
			view.alert_error("Nothing was done");
		}
	}
	//
	async C_create_review(){
		//
		var model = this;
		var view = this.view;
		//
		const { value: formValues } = await Swal.fire({
		title: 'Reviews',
		showCancelButton: true,
		cancelButtonText: 'Cancel',
		html:
			'<input id="swal-input1" class="swal2-input" placeholder="Your name"></input>' +
			'<input id="swal-input2" class="swal2-input" placeholder="Your rating 1-5"></input>' +
			'<textarea id="swal-input3" class="swal2-input" placeholder="Your review: What do you think of PracticePractice ?"></textarea>',
		focusConfirm: false,
		preConfirm: () => {
		return [
		  document.getElementById('swal-input1').value,
		  document.getElementById('swal-input2').value,
		  document.getElementById('swal-input3').value
		]
		}
		});
		//
		if (formValues){
			if (formValues[0] && formValues[1]) {
				
				setTimeout(function(){MathJax.typeset() }, 500);
				//
				const { value: Confirmation } = await Swal.fire({
				title: 'Review',
				showCancelButton: true,
				cancelButtonText: 'Cancel',
				html:
					'<div id="temp_mathjax"><p style="border-bottom:1px solid black;">Name: '+formValues[0]+'</p>' +
					'<p>Rating: '+formValues[1]+'</p> + <p>Review:'+formValues[2]+' </p> </div>',
					
				focusConfirm: false,
				preConfirm: () => {return [1]}
				});
				//
				if (Confirmation) {
					var name = formValues[0];
					var rating= formValues[1];
					var review= formValues[2];
					//
					model.M_create_review(name,review,rating);
					view.alert_loading();
					//
					model.xhttp.onreadystatechange = function () {
						if (this.readyState == 4 && this.status == 200) {
							var txt = this.responseText;
							eval(txt);
						}
					}
				}else{
					view.alert_error("Nothing was done");
				}
			}else{
				view.alert_error("Nothing was done");
			}
		}else{
			view.alert_error("Nothing was done");
		}
	}
	//
	C_delete_flashcard(fc_id){
		//
		var model = this;
		var view = this.view;
		//
		function process_warning() {
			//
			function process() {
				//
				model.M_delete_flashcard(fc_id);
				view.alert_loading();
				//
				model.xhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						var txt = this.responseText;
						eval(txt);
					}
				}
			}
			view.alert_warning_orCancel_go('Second confirmation, are you sure ?', process);
		}
		view.alert_warning_orCancel_go('You will be deleting this flashcard, are you sure ?', process_warning);
	}
	//
	C_view_flashcard(id,question,answer){
		//
		var model = this;
		var view = this.view;
		//
		Swal.fire({
		title: 'Flashcard',
		html:
			'<div id="temp_mathjax"><p style="border-bottom:1px solid black;">Front: '+question+'</p>' +
			'<p>Back: '+answer+'</p></div>',
		focusConfirm: false,
		});
		MathJax.typeset();
		//
	}
}









view = new view();
controller = new controller(view);
controller.C_lazy_loader();
controller.C_toggle_css();
controller.C_minor_ops();
controller.C_impression_track();
