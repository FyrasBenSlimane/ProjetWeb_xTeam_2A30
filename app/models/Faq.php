<?php

/**
 * FAQ Model
 * Handles database operations for FAQs and FAQ categories
 */
class Faq
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Get all FAQs
     * @return array
     */
    public function getAllFaqs()
    {
        $this->db->query('SELECT * FROM faqs ORDER BY id ASC');
        return $this->db->resultSet();
    }

    /**
     * Get all published FAQs
     * @return array
     */
    public function getPublishedFaqs()
    {
        $this->db->query('SELECT * FROM faqs WHERE is_published = 1 ORDER BY id ASC');
        return $this->db->resultSet();
    }

    /**
     * Get FAQs by category
     * @param string $category
     * @return array
     */
    public function getFaqsByCategory($category)
    {
        $this->db->query('SELECT * FROM faqs WHERE category = :category AND is_published = 1 ORDER BY id ASC');
        $this->db->bind(':category', $category);
        return $this->db->resultSet();
    }

    /**
     * Get FAQ by ID
     * @param int $id
     * @return object
     */
    public function getFaqById($id)
    {
        $this->db->query('SELECT * FROM faqs WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Add a new FAQ
     * @param array $data
     * @return bool
     */
    public function addFaq($data)
    {
        // Check if the table has display_order column
        $hasDisplayOrder = $this->checkIfColumnExists('faqs', 'display_order');

        if ($hasDisplayOrder) {
            $this->db->query('INSERT INTO faqs (question, answer, category, display_order, is_published) VALUES (:question, :answer, :category, :order, :is_published)');
            $this->db->bind(':order', $data['order']);
        } else {
            $this->db->query('INSERT INTO faqs (question, answer, category, is_published) VALUES (:question, :answer, :category, :is_published)');
        }

        // Bind values
        $this->db->bind(':question', $data['question']);
        $this->db->bind(':answer', $data['answer']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':is_published', $data['is_published']);

        // Execute
        return $this->db->execute();
    }

    /**
     * Update an FAQ
     * @param array $data
     * @return bool
     */
    public function updateFaq($data)
    {
        // Check if the table has display_order column
        $hasDisplayOrder = $this->checkIfColumnExists('faqs', 'display_order');

        if ($hasDisplayOrder) {
            $this->db->query('UPDATE faqs SET question = :question, answer = :answer, category = :category, display_order = :order, is_published = :is_published WHERE id = :id');
            $this->db->bind(':order', $data['order']);
        } else {
            $this->db->query('UPDATE faqs SET question = :question, answer = :answer, category = :category, is_published = :is_published WHERE id = :id');
        }

        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':question', $data['question']);
        $this->db->bind(':answer', $data['answer']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':is_published', $data['is_published']);

        // Execute
        return $this->db->execute();
    }

    /**
     * Delete an FAQ
     * @param int $id
     * @return bool
     */
    public function deleteFaq($id)
    {
        $this->db->query('DELETE FROM faqs WHERE id = :id');

        // Bind values
        $this->db->bind(':id', $id);

        // Execute
        return $this->db->execute();
    }

    /**
     * Get all FAQ categories
     * @return array
     */
    public function getAllCategories()
    {
        // First, check if the faq_categories table exists
        if ($this->checkIfTableExists('faq_categories')) {
            $this->db->query('SELECT * FROM faq_categories ORDER BY name ASC');
            return $this->db->resultSet();
        } else {
            // If table doesn't exist, extract unique categories from faqs table
            $this->db->query('SELECT DISTINCT category as name FROM faqs ORDER BY category ASC');
            $categories = $this->db->resultSet();

            // Transform results to match expected structure
            foreach ($categories as $key => $category) {
                if (empty($category->name)) {
                    unset($categories[$key]);
                    continue;
                }
                $category->id = $key + 1;
                $category->description = null;
            }

            return $categories;
        }
    }

    /**
     * Get category by ID
     * @param int $id
     * @return object|bool
     */
    public function getCategoryById($id)
    {
        // Validate that $id is a numeric value
        if (!is_numeric($id)) {
            return false;
        }

        $id = (int)$id; // Ensure $id is converted to integer

        // First, check if the faq_categories table exists
        if ($this->checkIfTableExists('faq_categories')) {
            $this->db->query('SELECT * FROM faq_categories WHERE id = :id');
            $this->db->bind(':id', $id);
            return $this->db->single();
        } else {
            // If table doesn't exist, try to find the category by position in distinct categories
            $this->db->query('SELECT DISTINCT category FROM faqs ORDER BY category ASC');
            $categories = $this->db->resultSet();

            $index = $id - 1;
            if (isset($categories[$index])) {
                $category = new stdClass();
                $category->id = $id;
                $category->name = $categories[$index]->category;
                $category->description = null;
                return $category;
            }

            return false;
        }
    }

    /**
     * Add a new category
     * @param array $data
     * @return bool
     */
    public function addCategory($data)
    {
        // First, ensure the faq_categories table exists
        $this->createFaqCategoriesTableIfNotExists();

        $this->db->query('INSERT INTO faq_categories (name) VALUES (:name)');

        // Bind values
        $this->db->bind(':name', $data['name']);

        // Execute
        return $this->db->execute();
    }

    /**
     * Delete a category
     * @param int $id
     * @return bool
     */
    public function deleteCategory($id)
    {
        // Only attempt to delete if the table exists
        if (!$this->checkIfTableExists('faq_categories')) {
            return false;
        }

        $this->db->query('DELETE FROM faq_categories WHERE id = :id');

        // Bind values
        $this->db->bind(':id', $id);

        // Execute
        return $this->db->execute();
    }

    /**
     * Check if a category has associated FAQs
     * @param int $id
     * @return bool
     */
    public function categoryHasFaqs($id)
    {
        // Check if faq_categories table exists first
        if (!$this->checkIfTableExists('faq_categories')) {
            return false;
        }

        // Get the category name
        $category = $this->getCategoryById($id);

        if (!$category) {
            return false;
        }

        // Check if any FAQs use this category
        $this->db->query('SELECT COUNT(*) as count FROM faqs WHERE category = :category');
        $this->db->bind(':category', $category->name);
        $result = $this->db->single();

        return ($result->count > 0);
    }

    /**
     * Check if a table exists in the database
     * @param string $table_name
     * @return bool
     */
    private function checkIfTableExists($table_name)
    {
        $this->db->query("SHOW TABLES LIKE :table_name");
        $this->db->bind(':table_name', $table_name);
        $result = $this->db->single();
        return !empty($result);
    }

    /**
     * Check if a column exists in the specified table
     * @param string $table_name
     * @param string $column_name
     * @return bool
     */
    private function checkIfColumnExists($table_name, $column_name)
    {
        $this->db->query("SHOW COLUMNS FROM {$table_name} LIKE :column_name");
        $this->db->bind(':column_name', $column_name);
        $result = $this->db->single();
        return !empty($result);
    }

    /**
     * Create the faq_categories table if it doesn't exist
     */
    private function createFaqCategoriesTableIfNotExists()
    {
        if (!$this->checkIfTableExists('faq_categories')) {
            $this->db->query('CREATE TABLE IF NOT EXISTS faq_categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                description TEXT NULL,
                display_order INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )');
            $this->db->execute();

            // Populate the table with existing categories from faqs table
            $this->db->query('SELECT DISTINCT category FROM faqs WHERE category IS NOT NULL AND category != ""');
            $categories = $this->db->resultSet();

            foreach ($categories as $category) {
                $this->db->query('INSERT INTO faq_categories (name) VALUES (:name)');
                $this->db->bind(':name', $category->category);
                $this->db->execute();
            }
        }
    }
}
