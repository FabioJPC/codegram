<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Story\CreateStoryRequest;
use App\Http\Resources\Story\StoryResource;
use App\Models\Story;
use App\Services\Api\StoryService;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function __construct(
        private StoryService $storyService,
    ){}

    public function store(CreateStoryRequest $request)
    {
        $story = $this->storyService->store(
            $request->user(),
            $request->file('image')
        );

        return response()->json(['data' => $story], 201);
    }

    public function destroy(Request $request, Story $story)
    {
        $this->storyService->destroy($request->user(), $story);

        return response()->noContent();
    }

    public function feed(Request $request)
    {
        $stories = $this->storyService->feed($request->user());

        return StoryResource::collection($stories);
    }
}
