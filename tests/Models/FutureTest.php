<?php

namespace Dixie\LaravelModelFuture\Tests\Models;

use Dixie\LaravelModelFuture\Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Dixie\LaravelModelFuture\Collections\FutureCollection;
use Dixie\LaravelModelFuture\Models\Future;

class FutureTest extends TestCase
{

    protected $future;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->future = $this->createFuturePlanFor($this->user, Carbon::now());
    }

    public function testItCastsItsDataToArray()
    {
        $this->assertInternalType('array', $this->future->data);
    }

    public function testItCastsItsDatesToCarbonInstances()
    {
        $this->assertEquals($this->future->getDates(), [
            'commit_at',
            'committed_at',
            'deleted_at',
            'created_at',
            'updated_at',
        ]);

        // Setup dates so that they are all filled
        //
        // committed date
        $this->future->committed_at = '1993-12-10';
        $this->future->save();

        // deleted_at date
        $this->future->delete();

        $this->assertInstanceOf(Carbon::class, $this->future->commit_at);
        $this->assertInstanceOf(Carbon::class, $this->future->committed_at);
        $this->assertInstanceOf(Carbon::class, $this->future->created_at);
        $this->assertInstanceOf(Carbon::class, $this->future->updated_at);
        $this->assertInstanceOf(Carbon::class, $this->future->deleted_at);
    }

    public function testItHasaMorphToRelationshipCalledFutureable()
    {
        $this->assertInstanceOf(MorphTo::class, $this->future->futureable());
    }

    public function testItReturnsACustomEloquentCollection()
    {
        $this->assertInstanceOf(FutureCollection::class, Future::all());
    }
}
