function uos_three($element,data) {

  // modified from view-source:http://mrdoob.github.io/three.js/examples/css3d_periodictable.html
	var camera, scene, renderer;
	var controls;

	var objects = [];
	var targets = { table: [], sphere: [], helix: [], grid: [] };


	camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
	camera.position.z = 3000;
	scene = new THREE.Scene();
	
	init();
	animate();
	
	function init() {
		var $nodes = uos.getAllChildEntities($element);
		$nodes.each(function (index) {
			jQuery(this).addClass('element');
			jQuery(this).addClass('three-d');
			console.log(this);
			var object = new THREE.CSS3DObject( this ); //??
			object.position.x = Math.random() * 4000 - 2000;
			object.position.y = Math.random() * 4000 - 2000;
			object.position.z = Math.random() * 4000 - 2000;
			scene.add(object);
			objects.push( object );
		});				

		//console.log(objects);

		var l = objects.length;
		
		// table 
		
		jQuery.each(objects, function(i,o) {
			var object = new THREE.Object3D();
			
			// we can use some element property here
			//object.position.x = ( table[ i + 3 ] * 140 ) - 1330;
			//object.position.y = - ( table[ i + 4 ] * 180 ) + 990;

			//targets.table.push( object );
		});
		
		// sphere
		
		var vector = new THREE.Vector3();
		
		jQuery.each(objects, function(i,o) {

			var phi = Math.acos( -1 + ( 2 * i ) / l );
			var theta = Math.sqrt( l * Math.PI ) * phi;

			var object = new THREE.Object3D();

			object.position.x = 400 * Math.cos( theta ) * Math.sin( phi );
			object.position.y = 400 * Math.sin( theta ) * Math.sin( phi );
			object.position.z = 400 * Math.cos( phi );

			vector.copy( object.position ).multiplyScalar( 2 );

			object.lookAt( vector );

			targets.sphere.push( object );

		});
		
		// helix
		var vector = new THREE.Vector3();					

		jQuery.each(objects, function(i,o) {
			var phi = i * 0.175 + Math.PI;

			var object = new THREE.Object3D();

			object.position.x = 900 * Math.sin( phi );
			object.position.y = - ( i * 8 ) + 450;
			object.position.z = 900 * Math.cos( phi );

			vector.x = object.position.x * 2;
			vector.y = object.position.y;
			vector.z = object.position.z * 2;

			object.lookAt( vector );

			targets.helix.push( object );					
		});
		
		// grid			

		jQuery.each(objects, function(i,o) {

			var object = new THREE.Object3D();

			object.position.x = ( ( i % 5 ) * 400 ) - 800;
			object.position.y = ( - ( Math.floor( i / 5 ) % 5 ) * 400 ) + 800;
			object.position.z = ( Math.floor( i / 25 ) ) * 1000 - 2000;

			targets.grid.push( object );

		});

		renderer = new THREE.CSS3DRenderer();
		renderer.setSize( window.innerWidth, window.innerHeight );
		renderer.domElement.style.position = 'absolute';
		jQuery('#container').html( renderer.domElement );
		
		controls = new THREE.TrackballControls( camera, renderer.domElement );
		controls.rotateSpeed = 0.5;
		controls.minDistance = 500;
		controls.maxDistance = 6000;
		controls.addEventListener( 'change', render );
		
		//transform( targets.helix, 5000 );
		transform( targets.sphere, 1000 );
	}	


	function transform( targets, duration ) {

		TWEEN.removeAll();
		console.log(objects.length);
		for ( var i = 0; i < objects.length; i ++ ) {

			var object = objects[ i ];
			var target = targets[ i ];

			new TWEEN.Tween( object.position )
				.to( { x: target.position.x, y: target.position.y, z: target.position.z }, Math.random() * duration + duration )
				.easing( TWEEN.Easing.Exponential.InOut )
				.start();

			new TWEEN.Tween( object.rotation )
				.to( { x: target.rotation.x, y: target.rotation.y, z: target.rotation.z }, Math.random() * duration + duration )
				.easing( TWEEN.Easing.Exponential.InOut )
				.start();

		}

		new TWEEN.Tween( this )
			.to( {}, duration * 2 )
			.onUpdate( render )
			.start();

	}

	function onWindowResize() {

		camera.aspect = window.innerWidth / window.innerHeight;
		camera.updateProjectionMatrix();

		renderer.setSize( window.innerWidth, window.innerHeight );

		render();

	}

	function animate() {

		requestAnimationFrame( animate );

		TWEEN.update();

		controls.update();

	}

	function render() {

		renderer.render( scene, camera );

	}		
}	
