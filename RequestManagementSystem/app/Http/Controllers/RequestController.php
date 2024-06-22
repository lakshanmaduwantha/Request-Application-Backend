<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $requests = RequestModel::with(['creator', 'assignee'])->get();
            return response()->json([
                'status' => 'success',
                'data' => $requests
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch requests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'created_on' => 'required|date',
                'location' => 'required|string|max:255',
                'service' => 'required|string|max:255',
                'status' => 'required|in:NEW,IN_PROGRESS,ON_HOLD,REJECTED,CANCELLED',
                'priority' => 'required|in:HIGH,MEDIUM,LOW',
                'department' => 'required|string|max:255',
                'requested_by' => 'required|exists:users,id',
                'assigned_to' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $newRequest = RequestModel::create($validator->validated());
            return response()->json([
                'status' => 'success',
                'message' => 'Request created successfully',
                'data' => $newRequest->load(['creator', 'assignee'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'created_on' => 'sometimes|date',
                'location' => 'sometimes|string|max:255',
                'service' => 'sometimes|string|max:255',
                'status' => 'sometimes|in:NEW,IN_PROGRESS,ON_HOLD,REJECTED,CANCELLED',
                'priority' => 'sometimes|in:HIGH,MEDIUM,LOW',
                'department' => 'sometimes|string|max:255',
                'requested_by' => 'sometimes|exists:users,id',
                'assigned_to' => 'sometimes|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $requestModel = RequestModel::findOrFail($id);
            $requestModel->update($validator->validated());
            return response()->json([
                'status' => 'success',
                'message' => 'Request updated successfully',
                'data' => $requestModel->load(['creator', 'assignee'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $request = RequestModel::findOrFail($id);
            $request->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Request deleted successfully'
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete request',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
