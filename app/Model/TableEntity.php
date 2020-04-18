<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class TableEntity extends Model
{
    protected $primaryKey;
    public $incrementing = false;
    private $image_path = 'images/upload/';

    private function prepareMessage(bool $success, $msg){
        $msgTemplate = "<div class='row'> <div class='col-md-7 alert alert-success alert-dismissible' role='alert'><a href='#' class='close' data-dismiss='alert' aria-label='close'>×</a><center>$msg</center></div></div>";
        $errTemplate = "<div class='row'> <div class='col-md-7 alert alert-warning alert-dismissible' role='alert'><a href='#' class='close' data-dismiss='alert' aria-label='close'>×</a><center>$msg</center></div></div>";
        return $success ? $msgTemplate : $errTemplate;
    }

    public function setTablePrimary(string $table, string $primaryKey = 'id')
    {
        $this->primaryKey = $primaryKey;
        $this->setTable($table);
    }

    public function updateTable($table, $primaryKey, $whereValue, $inputs, $file = null, $file_ref = null, $file_column_name = 'image_url')
    {
        try{
            $this->setTablePrimary($table, $primaryKey);
            unset($inputs['_token']);
            if($file != null){
                $filename = $file_ref. '.' . $file->getClientOriginalExtension();
                $file->move($this->image_path, $filename);
                $inputs[$file_column_name] = $this->image_path . $filename;
            }
            $this->where($primaryKey, '=', $whereValue)->update($inputs);
            return back()->with('msg', $this->prepareMessage(true, "Record Updated Successfully!"));
        }catch (\Throwable $e){
            return back()->with('msg', $this->prepareMessage(false, 'Error Occurs: '. $e->getMessage()));
        }
    }

    public function deactivate($table, $primaryKey, $primaryKeyValue)
    {
        try{
            $this->setTablePrimary($table, $primaryKey);
            $this->where($primaryKey, '=', $primaryKeyValue)->update(['active' => 0]);
            return back()->with('msg', $this->prepareMessage(true, "Deactivated Successfully!"));
        }catch (\Throwable $e){
            return back()->with('msg', $this->prepareMessage(false, 'Error Occurs: '. $e->getMessage()));
        }

    }

    public function activate($table, $primaryKey, $primaryKeyValue)
    {
        try{
            $this->setTablePrimary($table, $primaryKey);
            $this->where($primaryKey, '=', $primaryKeyValue)->update(['active' => 1]);
            return back()->with('msg', $this->prepareMessage(true, "Activated Successfully!"));
        }catch (\Throwable $e){
            return back()->with('msg', $this->prepareMessage(false, 'Error Occurs: '. $e->getMessage()));
        }

    }

    public function insertNewEntry($table, $primaryKeyValue, $inputs, $file = null, $file_ref = null, $showMessage = true)
    {
        try {
            $this->setTablePrimary($table, $primaryKeyValue);
            unset($inputs['_token']);
            if($file != null){
                $filename = $file_ref. '.' . $file->clientExtension();
                $file->move($this->image_path, $filename);
                $inputs['image_url'] = $this->image_path . $filename;
            }
            $this->forceFill($inputs);
            $this->save();
            if($showMessage)
                return back()->with('msg', $this->prepareMessage(true, 'Record Inserted Successfully!'));
        } catch (\Throwable $e) {
            if($showMessage)
                return back()->with('msg', $this->prepareMessage(false, 'Error Occurs: '. $e->getMessage()));
        }
    }

    public function getSingleItem($table, $primaryKey, $searchValue)
    {
        return $this->getSingleItemWithWhere($table, $primaryKey, [[$primaryKey, '=', $searchValue], ['active', '=', 1]] );
    }

    public function getItemList($table, $primaryKey = 'id', $onlyActive = false)
    {
        $where = [['active', '=', 1]];
        $this->setTablePrimary($table, $primaryKey);
        return $this->where($onlyActive ? $where : null)->get();
    }

    public function getSingleItemWithWhere($table, $primaryKey, $where)
    {
        $this->setTablePrimary($table, $primaryKey);
        return $this->where($where)->first();
    }

    public function getItemListWithWhere($table, $primaryKey, $where)
    {
        $this->setTablePrimary($table, $primaryKey);
        return $this->where( $where)->get();
    }
}
