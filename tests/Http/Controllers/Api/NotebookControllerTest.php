<?php

namespace Tests\Http\Controllers\Api;

use App\Models\Note;
use App\Services\FileUploadService;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

/**
 * Test Api methods (Check APP_ENV=testing)
 */
class NotebookControllerTest extends TestCase
{
    /** @var string */
    protected const ROUTE_API_NOTEBOOK = '/api/v1/notebook';
    /** @var string */
    protected const TABLE = 'notes';
    /** @var array */
    protected const NOTE_STRUCTURE = [
        'id',
        'full_name',
        'company',
        'phone',
        'email',
        'birth_date',

        'photo_url',
        'photo_need_delete',

        'created_at',
        'updated_at',
    ];

    public function testIndex(): void
    {
        $this->json(Request::METHOD_GET, self::ROUTE_API_NOTEBOOK)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => self::NOTE_STRUCTURE
                    ]
                ]
            );
    }

    public function testShow(): void
    {
        $payload = Note::factory()->createOne()->getAttributes();

        $this->json(
            Request::METHOD_GET,
            sprintf('%s/%s', self::ROUTE_API_NOTEBOOK, $payload['id'])
        )
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => self::NOTE_STRUCTURE
                ]
            );
    }

    public function testStore(): void
    {
        $note = Note::factory()->createOne()->getAttributes();
        unset($note['id']);
        $note['phone'] = $this->faker->phoneNumber;
        $note['email'] = $this->faker->email;

        $photo = UploadedFile::fake()->create(
            'photo.jpg',
            128
        );
        $payload = array_merge(
            $note,
            ['photo_file' => $photo]
        );

        $this->json(
            Request::METHOD_POST,
            self::ROUTE_API_NOTEBOOK,
            $payload
        )
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' => self::NOTE_STRUCTURE
                ]
            );
    }

    public function testUpdateAndChangeFile(): void
    {
        $note = Note::factory()->createOne()->getAttributes();
        $photo = UploadedFile::fake()->create(
            'photo.jpg',
            128
        );
        $payload = array_merge(
            $note,
            ['photo_file' => $photo]
        );

        $this->json(
            Request::METHOD_POST,
            sprintf('%s/%s', self::ROUTE_API_NOTEBOOK, $payload['id']),
            $payload
        )
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(
                [
                    'data' => self::NOTE_STRUCTURE
                ]
            );
    }

    public function testUpdateAndDeleteFile(): void
    {
        $note = Note::factory()->createOne()->getAttributes();

        $payload = array_merge(
            $note,
            ['photo_need_delete' => true],
            ['photo_file' => null]
        );

        $this->json(
            Request::METHOD_POST,
            sprintf('%s/%s', self::ROUTE_API_NOTEBOOK, $payload['id']),
            $payload
        )
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(
                [
                    'data' => self::NOTE_STRUCTURE
                ]
            );

        $note['photo_uuid'] = null;
        $note['photo_name'] = null;
        $this->assertDatabaseHas(self::TABLE, $note);
    }

    public function testDestroy()
    {
        $payload = Note::factory()->createOne()->getAttributes();

        $this->json(
            Request::METHOD_DELETE,
            sprintf('%s/%s', self::ROUTE_API_NOTEBOOK, $payload['id'])
        )
            ->assertNoContent();

        $this->assertDatabaseMissing(self::TABLE, $payload);
    }
}
