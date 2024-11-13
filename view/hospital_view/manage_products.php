<?php include '../includes/header.php'; ?>

<div class="product-list-container">
    <div class="product-list-header">
        <h1>Service List</h1>
    </div>
    
    <div class="product-list-controls">
        <div class="entries-control">
            Show 
            <select class="entries-select">
                <option>10</option>
                <option>25</option>
                <option>50</option>
                <option>100</option>
            </select>
            entries
        </div>
        
        <div class="search-control">
            <input type="text" class="search-input" placeholder="Search...">
            <i class="fas fa-search search-icon"></i>
        </div>
    </div>

    <table class="product-table">
        <thead>
            <tr>
                <th>Service <i class="fas fa-sort sort-icon"></i></th>
                <th>Description <i class="fas fa-sort sort-icon"></i></th>
                <th>Price <i class="fas fa-sort sort-icon"></i></th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Service Eight</td>
                <td>This is a sample service eight.</td>
                <td>$19</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-edit"><i class="fas fa-edit"></i></button>
                        <button class="btn-delete"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Service Five</td>
                <td>This is a sample service five.</td>
                <td>$86</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-edit"><i class="fas fa-edit"></i></button>
                        <button class="btn-delete"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
            <!-- More rows as needed -->
        </tbody>
    </table>

    <div class="product-pagination">
        <button class="pagination-button">Previous</button>
        <button class="pagination-button active">1</button>
        <button class="pagination-button">Next</button>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
