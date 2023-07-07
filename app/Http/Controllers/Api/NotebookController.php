<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\NoteListGetRequest;
use App\Http\Requests\Api\NoteStoreRequest;
use App\Http\Requests\Api\NoteUpdateRequest;
use App\Http\Resources\Api\NoteResource;
use App\Services\NotebookService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class NotebookController extends ApiController
{
    /**
     * @var NotebookService
     */
    private NotebookService $notebookService;

    /**
     * NotebookController constructor.
     * @param NotebookService $notebookService
     */
    public function __construct(NotebookService $notebookService)
    {
        $this->notebookService = $notebookService;
    }

    /**
     * @OA\Get(
     *      path="/notebook",
     *      @OA\Parameter(
     *         name="per_page", in="query", required=false,
     *         description="per_page",
     *         example="2",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Parameter(
     *         name="cursor", in="query", required=false,
     *         description="cursor",
     *         example="eyJpZCI6MywiX3BvaW50c1RvTmV4dEl0ZW1zIjp0cnVlfQ",
     *         @OA\Schema(type="string")
     *     ),
     *      operationId="getNotesList",
     *      tags={"Notebook"},
     *      summary="Get list of notes in notebook",
     *      description="Return list of notes",
     *      @OA\Response(
     *          response=200, description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NoteResource")
     *       ),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden")
     *     )
     * @param NoteListGetRequest $request
     * @return JsonResponse
     */
    public function index(NoteListGetRequest $request): JsonResponse
    {
        $notes = $this->notebookService->findAllNotesWithPagination($request);
        return NoteResource::collection($notes)->response();
    }

    /**
     * @OA\Post(
     *      path="/notebook",
     *      operationId="storeNotebook",
     *      tags={"Notebook"},
     *      summary="Store new note",
     *      description="Store and return new note",
     *      @OA\RequestBody(
     *           @OA\JsonContent(ref="#/components/schemas/NoteStoreRequest"),
     *
     *           @OA\MediaType(
     *               mediaType="multipart/form-data",
     * @OA\Schema(
     * required={"full_name", "phone", "email"},
     * @OA\Property(
     *     title="full_name",
     *     property="full_name",
     *     description="fio of the new notebook",
     *     example="Sheldon Lee Cooper",
     *     type="string"
     * ),
     * @OA\Property(
     *     title="company",
     *     property="company",
     *     description="Company name",
     *     example="Future",
     *     type="string"
     * ),
     * @OA\Property(
     *     title="phone",
     *     property="phone",
     *     description="phone number",
     *     example="+39138789252",
     *     type="string"
     * ),
     * @OA\Property(
     *     title="email",
     *     property="email",
     *     description="email",
     *     example="user@mail.ru",
     *     type="string"
     * ),
     * @OA\Property(
     *     title="birth_date",
     *     property="birth_date",
     *     description="date of birth",
     *     example="1995-01-25",
     *     type="Date"
     * ),
     * @OA\Property(
     *     title="photo_url",
     *     property="photo_url",
     *     description="photo_url",
     *     example="http:://notebook.ru/public/storage/qwerty-photo.jpeg",
     *     type="string"
     * ),
     * @OA\Property(
     *     title="photo_must_deleted",
     *     property="photo_must_deleted",
     *     description="should the photo be deleted (for true/false can use 0/1)",
     *     default="0",
     *     example="0",
     *     type="string"
     * ),
     * @OA\Property(
     *     title="photo_file",
     *     property="photo_file",
     *     description="binary photo file, max size 128 KiB",
     *     type="string",
     *     format="binary"
     * )),
     *     )),
     *      @OA\Response(
     *          response=201, description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Note")
     *       ),
     *      @OA\Response(response=400,description="Bad Request"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden")
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param NoteStoreRequest $request
     * @return JsonResponse
     */
    public function store(NoteStoreRequest $request): JsonResponse
    {
        $notebook = $this->notebookService->createNote($request);

        return (new NoteResource($notebook))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *      path="/notebook/{id}",
     *      operationId="findNotebookById",
     *      tags={"Notebook"},
     *      summary="Get note information",
     *      description="Return note",
     *      @OA\Parameter(
     *          name="id",
     *          description="Notebook id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200, description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Note")
     *       ),
     *      @OA\Response(response=400,description="Bad Request"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Not Found")
     * )
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $noteById = $this->notebookService->findNoteById($id);
        return (new NoteResource($noteById))->response();
    }

    /**
     * @OA\Post(
     *      path="/notebook/{id}",
     *      operationId="updateNotebook",
     *      tags={"Notebook"},
     *      summary="Update existing note",
     *      description="Return updated note data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Notebook id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *           @OA\JsonContent(ref="#/components/schemas/NoteUpdateRequest"),
     *           @OA\MediaType(
     *               mediaType="multipart/form-data",
     * @OA\Schema(
     * @OA\Property(
     *     title="full_name",
     *     property="full_name",
     *     description="fio of the new notebook",
     *     example="Sheldon Lee Cooper",
     *     type="string"
     * ),
     * @OA\Property(
     *     title="company",
     *     property="company",
     *     description="Company name",
     *     example="Future",
     *     type="string"
     * ),
     * @OA\Property(
     *     title="phone",
     *     property="phone",
     *     description="phone number",
     *     example="+39138789252",
     *     type="string"
     * ),
     * @OA\Property(
     *     title="email",
     *     property="email",
     *     description="email",
     *     example="user@mail.ru",
     *     type="string"
     * ),
     * @OA\Property(
     *     title="birth_date",
     *     property="birth_date",
     *     description="date of birth",
     *     example="1995-01-25",
     *     type="Date"
     * ),
     * @OA\Property(
     *     title="photo_url",
     *     property="photo_url",
     *     description="photo_url",
     *     example="http:://notebook.ru/public/storage/qwerty-photo.jpeg",
     *     type="string"
     * ),
     * @OA\Property(
     *     title="photo_must_deleted",
     *     property="photo_must_deleted",
     *     description="should the photo be deleted (for true/false can use 0/1)",
     *     default=0,
     *     example="0",
     *     type="string"
     * ),
     * @OA\Property(
     *     title="photo_file",
     *     property="photo_file",
     *     description="binary photo file, max size 128 KiB",
     *     type="string",
     *     format="binary"
     * )),
     *    )),
     *      @OA\Response(
     *          response=202, description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Note")
     *       ),
     *      @OA\Response(response=400,description="Bad Request"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Not Found")
     * )
     *
     * @param NoteUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(NoteUpdateRequest $request, int $id): JsonResponse
    {
        $noteById = $this->notebookService->updateById($request, $id);

        return (new NoteResource($noteById))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(
     *      path="/notebook/{id}",
     *      operationId="deleteNotebook",
     *      tags={"Notebook"},
     *      summary="Delete existing note",
     *      description="Delete a record and return no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Notebook id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=204, description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Not Found")
     * )
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(int $id): JsonResponse
    {
        $this->notebookService->deleteById($id);

        return response()->json(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}
