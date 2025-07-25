    <?php

    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Resources\Json\JsonResource;

    if (!function_exists('success')) {
        /**
         * Return a successful JSON response
         *
         * @param mixed $data
         * @param string|null $message
         * @param int $statusCode
         * @param array $extra Extra fields like tokens
         * @return JsonResponse
         */
        function success(mixed $data, string|null $message = null, int $statusCode = 200, array $extra = []): JsonResponse
        {
            if ($data instanceof JsonResource) {
                $data->additional([
                    "message" => $message,
                    'status' => true,
                ]);

                if (!empty($extra)) {
                    $data->additional($extra);
                }

                return $data->response()->setStatusCode($statusCode);
            }

            $response = [
                'status' => true,
                'message' => $message,
                'data' => $data,
            ];

            if (!empty($extra)) {
                $response = array_merge($response, $extra);
            }

            return response()->json($response, $statusCode);
        }
    }

    if (!function_exists('error')) {
        /**
         * Return an error JSON response
         *
         * @param string|null $message
         * @param int $statusCode
         * @return JsonResponse
         */
        function error(string|null $message = null, int $statusCode = 400): JsonResponse
        {
            return response()->json([
                'status' => false,
                'message' => $message,
            ], $statusCode);
        }
    }
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Database\Eloquent\Model;
    
    if (!function_exists('upload_file_to_model')) {
        /**
         *
         * @param \Illuminate\Http\UploadedFile $file
         * @param \Illuminate\Database\Eloquent\Model $model
         * @param string $field (Optional) The field in the model to store the file path
         * @param string $folder (Optional) The folder where the file will be stored (default is 'uploads')
         * @return string|null
         */
        function upload_file_to_model($file, Model $model, $field = 'file_path', $folder = 'uploads')
        {
            if (!$file->isValid()) {
                return null;
            }
            
            $path = $file->store($folder, 'public');
    
            $model->$field = $path;
    
            $model->save();
    
            return $path;
        }
    }