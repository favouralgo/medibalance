<?php include '../includes/header.php'; ?>

<div class="managecustomers-list-container">
    <!-- <div class="managecustomers-list-header">
        <h1>Service List</h1>
    </div>
     -->
    <div class="managecustomers-list-controls">
        <div class="managecustomers-entries-control">
            Show 
            <select class="managecustomers-entries-select">
                <option>10</option>
                <option>25</option>
                <option>50</option>
                <option>100</option>
            </select>
            entries
        </div>
        
        <div class="managecustomers-search-control">
            <input type="text" class="managecustomers-search-input" placeholder="Search...">
            <i class="fas fa-search managecustomers-search-icon"></i>
        </div>
    </div>

    <table class="managecustomers-table">
        <thead>
            <tr>
                <th>Patient Name <i class="fas fa-sort sort-icon"></i></th>
                <th>Email <i class="fas fa-sort sort-icon"></i></th>
                <th>Phone Number <i class="fas fa-sort sort-icon"></i></th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>John Doe</td>
                <td>johndoe@gmail.com</td>
                <td>+23350123456789</td>
                <td>
                    <div class="managecustomers-action-buttons">
                        <button class="managecustomers-btn-edit"><i class="fas fa-edit"></i></button>
                        <button class="managecustomers-btn-delete"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Jane Doe</td>
                <td>janedoe@gmail.com</td>
                <td>+23350123456789</td>
                <td>
                    <div class="managecustomers-action-buttons">
                        <button class="managecustomers-btn-edit"><i class="fas fa-edit"></i></button>
                        <button class="managecustomers-btn-delete"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
            <!-- More rows as needed -->
        </tbody>
    </table>

    <div class="managecustomers-pagination">
        <button class="managecustomers-pagination-button">Previous</button>
        <button class="managecustomers-pagination-button active">1</button>
        <button class="managecustomers-pagination-button">Next</button>
    </div>
</div>

<?php include '../includes/footer.php'; ?>