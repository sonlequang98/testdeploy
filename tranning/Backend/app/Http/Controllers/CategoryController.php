<?php

namespace Backend\Http\Controllers;

use Backend\Enums\Message;
use Backend\Http\Requests\PostCategoryRequest;
use Backend\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class CategoryController extends Controller
{
    protected $category;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(CategoryRepositoryInterface $category)
    {
        $this->category = $category;
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostCategoryRequest $request)
    {
        if ($this->category->create($request)) {
            return response()->json([
                'data' => true,
            ], 200);
        }
        return response()->json([
            'message' => Message::processError,
        ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json([
            'data' => $this->category->get($id),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->category->delete($id)) {
            return response()->json([
                'data' => true,
            ]);
        }
        return response()->json([
                'message' => Message::deleteCatError,
        ], 400);

    }

    public function getParentCategory()
    {
        return response()->json([
            'data' => $this->category->getParentCategory(),
        ]);
    }

    public function getChildCategory($parent_id)
    {
        return response()->json([
            'data' => $this->category->getChildCategory($parent_id),
        ]);
    }

    public function updateParent(Request $request, $id)
    {
        $category = $this->category->get($id);
        $validator = Validator::make($request->all(), [
            'category_name' => [
                'required',
                Rule::unique('categories')->ignore($category),
            ],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => false,
                'error' => [
                    'message' => Message::processError,
                ],
            ], 400);
        }
        if ($this->category->updateParent($id, $request)) {
            return response()->json([
                'data' => 'true',
            ]);
        }
        return response()->json([
            'data' => false,
            'error' => [
                'message' => Message::processError,
            ],
        ], 400);

    }

    public function updateChildrent(Request $request, $id)
    {
        $category = $this->category->get($id);
        $validator = Validator::make($request->all(), [
            'category_name' => [
                'required',
                Rule::unique('categories')->ignore($category),
            ],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => false,
                'error' => [
                    'message' => Message::processError,
                ],
            ], 400);
        }
        if ($this->category->updateChildrent($id, $request)) {
            return response()->json([
                'data' => 'true',
            ]);
        }
        return response()->json([
            'data' => false,
            'error' => [
                'message' => Message::processError,
            ],
        ], 400);

    }

    public function getCatParentHaveChild()
    {
       
        return response()->json([
            'data' => $this->category->getCatParentHaveChild()
        ]);
    }

}
