<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	use Siakad\Program;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class ProgramController extends Controller
	{
		public function index()
		{
			$program = Program::where('role_id', \Auth::user() -> role_id) -> first();
			if(!$program) return view('program.create');
			return view('program.index', compact('program'));
		}
		
		public function store()
		{
			$input = array_only(Input::all(), ['program']);
			$input['role_id'] = \Auth::user() -> role_id;
			Program::create($input);
			return Redirect::route('program.index') -> with('message', 'Program Kerja berhasil disimpan');
		}
		
		public function edit()
		{
			$program = Program::where('role_id', \Auth::user() -> role_id) -> first();
			if(!$program) return view('program.create');
			return view('program.edit', compact('program'));
		}
		
		
		public function update()
		{
			$input = array_only(Input::all(), ['program']);
			
			$program = Program::where('role_id', \Auth::user() -> role_id) -> first();
			$program -> update($input);
			
			return Redirect::route('program.index') -> with('message', 'Program Kerja berhasil diperbarui');
		}
	}
