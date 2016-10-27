<?php
/**
 * LiquidBox PHP PDO Extension
 */
namespace LiquidBox\Extension;

class PDO extends \PDO
{
    public function __construct($dsn, $username = '', $password = '', array $driverOptions = array())
    {
        parent::__construct($dsn, $username, $password, $driverOptions);

        $this->setAttribute(static::ATTR_DEFAULT_FETCH_MODE, static::FETCH_ASSOC);
        $this->setAttribute(static::ATTR_STATEMENT_CLASS, array(__NAMESPACE__.'\PDOStatement', array($this)));
    }

    /**
     * @param string $table
     * @param mixed  $values String, array, or PDOStatement
     *
     * @return bool
     */
    public function insert($table, $values)
    {
        if ($values instanceof PDOStatement) {
            $values = $values->fetchAll();
        }
        if (is_array($values)) {
            if (key($values) !== 0) {
                $values = sprintf(
                    '(%s) VALUES ("%s")',
                    implode(',', array_keys($values)),
                    implode('","', array_values($values))
                );
            } else {
                $values = sprintf(
                    '(%s) VALUES ("%s")',
                    implode(',', array_keys($values[0])),
                    implode('","', array_values($values)) // DO SOMETHING TO LOOP THROUGH HERE...
                );
            }
        }

        return $this->query(sprintf('INSERT INTO %s %s', $table, $values));
    }

    /**
     * @param mixed  $tables String or array
     * @param string $where
     * @param string $order
     * @param string $limit
     *
     * @return bool
     */
    public function delete($tables, $where = null, $order = null, $limit = null)
    {
        if (is_array($tables)) {
            $tables = implode(',', $tables);
        }
        if ($where !== null) {
            $where = ' WHERE '.$where;
        }
        if ($order !== null) {
            $order = ' ORDER BY ' . (is_array($order) ? implode(',', $order) : $order);
        }
        if ($limit !== null) {
            $limit = ' LIMIT ' . (is_array($limit) ? vsprintf('%d,%d', $limit) : $limit);
        }

        return $this->query('DELETE FROM '.$tables.implode(array_filter(array($where, $order, $limit))));
    }

    /**
     * @param mixed  $columns String or array
     * @param string $table
     * @param string $where
     * @param string $order
     * @param string $limit
     *
     * @return \LiquidBox\Extension\PDOStatement
     */
    public function select($columns, $table, $where = null, $order = null, $limit = null)
    {
        if (is_array($columns)) {
            $columns = implode(',', $columns);
        }
        if ($where !== null) {
            $where = ' WHERE ' . $where;
        }
        if ($order !== null) {
            $order = ' ORDER BY ' . (is_array($order) ? implode(',', $order) : $order);
        }
        if ($limit !== null) {
            $limit = ' LIMIT ' . (is_array($limit) ? vsprintf('%d,%d', $limit) : $limit);
        }

        return $this->query(
            sprintf('SELECT %s FROM %s', $columns, $table).
            implode(array_filter(array($where, $order, $limit)))
        );
    }

    /**
     * @param mixed  $tables String or array
     * @param mixed  $values String or array
     * @param string $where
     * @param string $order
     * @param string $limit
     *
     * @return bool
     */
    public function update($tables, $values, $where = null, $order = null, $limit = null)
    {
        if (is_array($tables)) {
            $tables = implode(',', $tables);
        }
        if (is_array($values)) {
            foreach ($values as $column => $value) {
                $values[$column] = $column.'="'.$value.'"';
            }
            $values = implode(',', $values);
        }
        if ($where !== null) {
            $where = ' WHERE ' . $where;
        }
        if ($order !== null) {
            $order = ' ORDER BY ' . (is_array($order) ? implode(',', $order) : $order);
        }
        if ($limit !== null) {
            $limit = ' LIMIT ' . (is_array($limit) ? vsprintf('%d,%d', $limit) : $limit);
        }

        return $this->query(
            sprintf('UPDATE %s SET %s', $tables, $values).
            implode(array_filter(array($where, $order, $limit)))
        );
    }

    /**
     * @param mixed  $statement Array or string
     * @param string $classname Optional; Namespaced class name
     *
     * @return mixed
     */
    public function query($statement, $classname = null)
    {
        if ($classname === null) {
            return parent::query(is_array($statement) ? call_user_func_array('sprintf', $statement) : $statement);
        }

        return parent::query(
            is_array($statement) ? call_user_func_array('sprintf', $statement) : $statement,
            static::FETCH_CLASS,
            $classname,
            array($this)
        );
    }
}
