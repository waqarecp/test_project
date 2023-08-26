@extends('layouts.app')

@section('content')
<style>
    .btn-group  .btn {
        margin-right: 5px; /* Adjust the margin as needed */
    }
</style>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Task Dashboard</h1>
        <div>
            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#addTaskModal">Add New Task</a>
            <a href="{{ route('tasks.sync') }}" class="btn btn-secondary">Sync Tasks from API</a>
        </div>
    </div>
    <table id="taskTable" class="table table-bordered table-hover table-striped mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $count = '1';
            @endphp
            @foreach($tasks as $task)
                <tr>
                    <td>{{ $count++ }}</td>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td>
                        @if($task->status=='pending')
                        <span class="badge badge-warning">{{ucfirst($task->status)}}</span>
                        @else
                        <span class="badge badge-success">{{ucfirst($task->status)}}</span>
                        @endif
                    <td>
                    <div class="btn-group">
                            <a href="javascript:void(0)" class="btn btn-sm btn-info edit-task" data-id="{{ $task->id }}" data-title="{{ $task->title }}" data-description="{{ $task->description }}" data-status="{{ $task->status }}">
                                <i class="fas fa-edit"></i> <!-- Font Awesome Edit Icon -->
                            </a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete Row" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash-alt"></i> <!-- Font Awesome Trash Icon -->
                                </button>
                            </form>
                            <button class="btn btn-sm btn-primary" title="Edit Status" data-toggle="modal" data-target="#updateStatusModal{{ $task->id }}">
                                <i class="fas fa-check-circle"></i> <!-- Font Awesome Check Circle Icon -->
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="updateStatusModal{{ $task->id }}" tabindex="-1" aria-labelledby="updateStatusModalLabel{{ $task->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateStatusModalLabel{{ $task->id }}">Update Status</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('tasks.updateStatus', $task) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <p>Update status for: {{ $task->title }}</p>
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select class="form-control" id="status" name="status">
                                                        <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Add New Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{$errors->any()}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div id="successMessage" class="alert alert-success" style="display: none;"></div>
                <form id="addTaskForm" action="{{ route('tasks.store') }}" method="POST">
                @csrf
                    <div class="modal-body">
                        <!-- Add your form fields for adding a new task -->
                        <div class="form-group">
                            <label for="addtitle">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="addtitle" name="title" required>
                            <div class="invalid-title-error text-danger"></div>
                        </div>
                        <div class="form-group">
                            <label for="adddescription">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="adddescription" name="description" rows="3" required></textarea>
                            <div class="invalid-description-error text-danger"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="successeditMessage" class="alert alert-success" style="display: none;"></div>
                <form id="editTaskForm" action="{{ route('tasks.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <!-- Hidden input for task ID -->
                        <input type="hidden" id="task_id" name="task_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript section -->
    <script>
        $(document).ready(function () {
            $('#taskTable').DataTable({
                "order": [[0, "desc"]]
            });
            // Submit new task form using AJAX
            $('#addTaskForm').on('submit', function (event) {
                event.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        // Display success message
                        if (response.status === 'success') {
                            $('#successMessage').text(response.message).fadeIn();
                            
                           // Hide after 3 seconds
                            setTimeout(function () {
                                $('#successMessage').fadeOut(function () {
                                    // After hiding, refresh the page
                                    location.reload();
                                });
                            }, 1000);
                        }
                    },
                    error: function (error) {
                        if (error.status === 422) {
                            var errors = error.responseJSON.errors;
                            // Reset previous error states
                            $('#addTaskForm').find('.form-control').removeClass('is-invalid');
                            $('#addTaskForm').find('.invalid-feedback').empty();

                            // Display validation errors
                            for (var field in errors) {
                                if (errors.hasOwnProperty(field)) {
                                    var errorMessage = errors[field][0];
                                    $('#addTaskForm').find('#add' + field).addClass('is-invalid');
                                    $('#addTaskForm').find('.invalid-' + field + '-error').text(errorMessage);
                                }
                            }
                        }
                        // Handle other errors if needed
                    }

                });
            });
        });

        $('.edit-task').on('click', function () {
            var taskId = $(this).data('id');
            var title = $(this).data('title');
            var description = $(this).data('description');
            var status = $(this).data('status');

            $('#editTaskModal #task_id').val(taskId);
            $('#editTaskModal #title').val(title);
            $('#editTaskModal #description').val(description);

            $('#editTaskModal').modal('show');
        });

        // Submit edit form using AJAX
        $('#editTaskForm').on('submit', function (event) {
            event.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                        // Display success message
                        if (response.status === 'success') {
                            $('#successeditMessage').text(response.message).fadeIn();
                            
                           // Hide after 3 seconds
                            setTimeout(function () {
                                $('#successeditMessage').fadeOut(function () {
                                    // After hiding, refresh the page
                                    location.reload();
                                });
                            }, 1000);
                        }
                    },
                    error: function (error) {
                        if (error.status === 422) {
                            var errors = error.responseJSON.errors;
                            // Display validation errors
                            for (var field in errors) {
                                if (errors.hasOwnProperty(field)) {
                                    var errorMessage = errors[field][0];
                                    $('#' + field).addClass('is-invalid');
                                    $('#' + field + '-error').text(errorMessage);
                                }
                            }
                        }
                        // Handle other errors if needed
                    }
            });
        });

        // Function to fetch and display a new quote
        function syncAPI() {
            $.ajax({
                url: "{{ route('tasks.sync') }}", // The route to your quote-fetching controller method
                method: 'GET',
                success: function (response) {
                    // Update your HTML element with the fetched quote
                    console.log("API has been called");
                },
                error: function () {
                    console.error('Error fetching quote');
                }
            });
        }

        // Fetch a quote immediately on page load
        syncAPI();

        // Fetch a new quote every 5 minutes
        setInterval(syncAPI, 5 * 60 * 1000); // 5 minutes in milliseconds
    </script>
@endsection
