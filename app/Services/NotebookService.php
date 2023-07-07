<?php

namespace App\Services;

use App\Exceptions\Api\NotFoundNoteException;
use App\Http\Requests\Api\NoteListGetRequest;
use App\Http\Requests\Api\NoteStoreRequest;
use App\Http\Requests\Api\NoteUpdateRequest;
use App\Models\Note;
use Exception;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class NotebookService
{
    private const PER_PAGE = 5;
    private FileUploadService $fileUploadService;
    private NoteCacheService $cacheService;

    public function __construct(
        FileUploadService $fileUploadService,
        NoteCacheService  $cacheService
    )
    {
        $this->fileUploadService = $fileUploadService;
        $this->cacheService = $cacheService;
    }

    /**
     * @param NoteListGetRequest $request
     * @return CursorPaginator
     */
    public function findAllNotesWithPagination(NoteListGetRequest $request): CursorPaginator
    {
        $perPage = $request->get('per_page');
        $cursor = $request->get('cursor');

        return Note::query()
            ->orderBy('id')
            ->cursorPaginate(
                $perPage ?? self::PER_PAGE,
                ['*'],
                'cursor',
                $cursor
            );
    }

    /**
     * @param int $id
     * @return Builder|Builder[]|Collection|Model|mixed|null
     */
    public function findNoteById(int $id): mixed
    {
        return $this->cacheService->getFromCache($id, static function () use ($id) {
            return Note::query()->findOr($id, static function () {
                throw new NotFoundNoteException();
            });
        });
    }

    /**
     * @param NoteStoreRequest $request
     * @return Builder|Model
     */
    public function createNote(NoteStoreRequest $request): Model|Builder
    {
        $file = $request->file('photo_file');
        $params = $request->all();

        $fileUuid = FileUploadService::getFileUuid();
        $fileName = $file?->getClientOriginalName();
        $newFilePath = FileUploadService::getFilePath(
            $fileUuid,
            $fileName
        );

        if ($file) {
            $this->fileUploadService->upload($file, $newFilePath);
        }

        $params['photo_uuid'] = $file ? $fileUuid : null;
        $params['photo_name'] = $file ? $fileName : null;

        return $this->cacheService->rememberToCache(
            $this->cacheService->getKeyByArray($params),
            Note::query()->create($params)
        );
    }

    /**
     * из NoteStoreRequest придет info photo
     * photo_url
     * photo_must_deleted
     * photo_file
     *
     * @param NoteUpdateRequest $request
     * @param int $id
     * @return Note
     */
    public function updateById(NoteUpdateRequest $request, int $id): Note
    {
        $file = $request->file('photo_file');

        /** @var Note $currentNote */
        $currentNote = $this->findNoteById($id);
        $currentNote->fill($request->all());

        if ($file || $request->get('photo_must_deleted')) {

            $this->deletePhotoByNote($currentNote);

            $fileUuid = FileUploadService::getFileUuid();
            $fileName = $file?->getClientOriginalName();
            $newFilePath = FileUploadService::getFilePath(
                $fileUuid,
                $fileName
            );

            if ($file) {
                $this->fileUploadService->upload($file, $newFilePath);
            }

            $currentNote->photo_uuid = $file ? $fileUuid : null;
            $currentNote->photo_name = $file ? $fileName : null;
        }

        $currentNote->save();

        $this->cacheService->setToCache(
            $this->cacheService->getKeyByModel($currentNote),
            $currentNote
        );

        return $currentNote;
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function deleteById(int $id): void
    {
        $currentNote = $this->findNoteById($id);
        $this->deletePhotoByNote($currentNote);

        $this->cacheService->deleteFromCache(
            $this->cacheService->getKeyByModel($currentNote),
        );

        $currentNote->delete();
    }

    /**
     * @param Note $currentNote
     * @return void
     */
    private function deletePhotoByNote(Note $currentNote): void
    {
        if ($currentNote->photo_uuid && $currentNote->photo_name) {
            $this->fileUploadService->delete(
                FileUploadService::getFilePath($currentNote->photo_uuid, $currentNote->photo_name)
            );
        }
    }
}
