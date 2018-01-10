<?php

namespace HttpOz\Roles\Tests\Unit;

use HttpOz\Roles\Models\Role;
use HttpOz\Roles\Tests\Stubs\User;
use HttpOz\Roles\Tests\TestCase;

class HasRoleTraitTest extends TestCase {
	public function testCanAttachRole() {
		$user        = factory( User::class )->create( [
			'name' => 'The Oz'
		] );
		$adminRole   = factory( Role::class )->create();
		$managerRole = factory( Role::class )->create( [ 'name' => 'Manager', 'slug' => 'manager' ] );

		$user->attachRole( $adminRole );

		$this->assertEquals( 1, count( $user->roles()->count() ) );
	}

	public function testCanDetachRole() {
		$user        = factory( User::class )->create( [
			'name' => 'The Oz'
		] );
		$adminRole   = factory( Role::class )->create();
		$managerRole = factory( Role::class )->create( [ 'name' => 'Manager', 'slug' => 'manager' ] );

		$user->attachRole( $adminRole );
		$user->attachRole( $managerRole );

		$user->detachRole( $adminRole );

		$this->assertEquals( 1, $user->roles()->count() );
		$this->assertEquals( 'manager', $user->roles()->first()->slug );
	}

	public function testCanDetachAllRoles() {
		$user        = factory( User::class )->create( [
			'name' => 'The Oz'
		] );
		$adminRole   = factory( Role::class )->create();
		$managerRole = factory( Role::class )->create( [ 'name' => 'Manager', 'slug' => 'manager' ] );

		$user->attachRole( $adminRole );
		$user->attachRole( $managerRole );

		$this->assertEquals( 2, $user->roles()->count() );

		$user->detachAllRoles();

		$this->assertEquals( 0, $user->roles()->count() );
	}

	public function testHasRole() {
		$user        = factory( User::class )->create( [
			'name' => 'The Oz'
		] );
		$adminRole   = factory( Role::class )->create();
		$managerRole = factory( Role::class )->create( [ 'name' => 'Manager', 'slug' => 'manager' ] );
		$user->attachRole($adminRole);
		$user->attachRole($managerRole);

		$this->assertTrue($user->hasRole('admin'));
		$this->assertTrue($user->hasRole('manager'));

		config(['roles.cache.enabled' => true]);

		$this->assertTrue($user->hasRole('admin'));
		$this->assertTrue($user->hasRole('manager'));
	}

	public function testIsRole() {
		$user        = factory( User::class )->create( [
			'name' => 'The Oz'
		] );
		$adminRole   = factory( Role::class )->create();
		$user->attachRole($adminRole);

		$this->assertTrue($user->isRole('admin'));
	}
}