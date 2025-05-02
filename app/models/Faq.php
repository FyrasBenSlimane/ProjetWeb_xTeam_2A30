<?php

/**
 * FAQ Model
 * Handles database operations for FAQs and FAQ categories
 */
class Faq {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Get all FAQs
     * @return array
     */
    public function getAllFaqs() {
        $this->db->query('SELECT * FROM faqs ORDER BY display_order ASC, id ASC');
        return $this->db->resultSet();
    }

    /**
     * Get all published FAQs
     * @return array
     */
    public function getPublishedFaqs() {
        $this->db->query('SELECT * FROM faqs WHERE is_published = 1 ORDER BY display_order ASC, id ASC');
        return $this->db->resultSet();
    }

    /**
     * Get FAQs by category
     * @param string $category
     * @return array
     */
    public function getFaqsByCategory($category) {
        $this->db->query('SELECT * FROM faqs WHERE category = :category AND is_published = 1 ORDER BY display_order ASC, id ASC');
        $this->db->bind(':category', $category);
        return $this->db->resultSet();
    }

    /**
     * Get FAQ by ID
     * @param int $id
     * @return object
     */
    public function getFaqById($id) {
        $this->db->query('SELECT * FROM faqs WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Add a new FAQ
     * @param array $data
     * @return bool
     */
    public function addFaq($data) {
        $this->db->query('INSERT INTO faqs (question, answer, category, display_order, is_published) VALUES (:question, :answer, :category, :order, :is_published)');
        
        // Bind values
        $this->db->bind(':question', $data['question']);
        $this->db->bind(':answer', $data['answer']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':order', $data['order']);
        $this->db->bind(':is_published', $data['is_published']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update an FAQ
     * @param array $data
     * @return bool
     */
    public function updateFaq($data) {
        $this->db->query('UPDATE faqs SET question = :question, answer = :answer, category = :category, display_order = :order, is_published = :is_published WHERE id = :id');
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':question', $data['question']);
        $this->db->bind(':answer', $data['answer']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':order', $data['order']);
        $this->db->bind(':is_published', $data['is_published']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete an FAQ
     * @param int $id
     * @return bool
     */
    public function deleteFaq($id) {
        $this->db->query('DELETE FROM faqs WHERE id = :id');
        
        // Bind values
        $this->db->bind(':id', $id);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get all FAQ categories
     * @return array
     */
    public function getAllCategories() {
        $this->db->query('SELECT * FROM faq_categories ORDER BY name ASC');
        return $this->db->resultSet();
    }

    /**
     * Get category by ID
     * @param int $id
     * @return object
     */
    public function getCategoryById($id) {
        $this->db->query('SELECT * FROM faq_categories WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Add a new category
     * @param array $data
     * @return bool
     */
    public function addCategory($data) {
        $this->db->query('INSERT INTO faq_categories (name) VALUES (:name)');
        
        // Bind values
        $this->db->bind(':name', $data['name']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete a category
     * @param int $id
     * @return bool
     */
    public function deleteCategory($id) {
        $this->db->query('DELETE FROM faq_categories WHERE id = :id');
        
        // Bind values
        $this->db->bind(':id', $id);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if a category has associated FAQs
     * @param int $id
     * @return bool
     */
    public function categoryHasFaqs($id) {
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
}